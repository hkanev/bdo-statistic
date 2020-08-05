<?php
// src/AppBundle/Command/GreetCommand.php
namespace AppBundle\Command;

use AppBundle\Entity\Dates;
use AppBundle\Entity\Items;
use AppBundle\Entity\ItemsPerDay;
use DateTime;
use Doctrine\ORM\EntityManager;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\VarDumper\VarDumper;

class GreetCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('demo:greet')
            ->setDescription('Greet someone')
            ->addArgument(
                'name',
                InputArgument::OPTIONAL,
                'Who do you want to greet?'
            )
            ->addOption(
                'yell',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will yell in uppercase letters'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $data = $this->getData();
        $date = $this->getDate($data['time']);

        foreach ($data['data'] as $row) {
            $item = $this->getEntityManager()->getRepository(Items::class)->findOneBy(['itemId' => $row['mainKey']]);

            if(!$item){
                $item = $this->createItem($row);
            }

            /** @var ItemsPerDay $itemPerDay */
            $itemPerDay = $this->getEntityManager()->getRepository(ItemsPerDay::class)
                ->createQueryBuilder('ipd')
                ->where('ipd.date = :date')
                ->andWhere('ipd.item = :item')
                ->setParameter('date', $date->getId())
                ->setParameter('item', $item->getId())
                ->getQuery()->getOneOrNullResult();

            if(!$itemPerDay) {
                return $this->createItemPerDay($item, $date, $row['lastHour']);
            }

            $itemPerDay->setQuantity($itemPerDay->getQuantity() + $row['lastHour']);
            $this->getEntityManager()->persist($itemPerDay);
        }

        $this->getEntityManager()->flush();
    }

    protected function getData() {
        $client = new Client();
        $response = $client->request('GET', 'https://bdowhaletracker.com/market-data-eu.json');
        return \GuzzleHttp\json_decode($response->getBody(), true);
    }

    /*
     * @return Dates
     */
    protected function getDate($timestamp)
    {
        $date = DateTime::createFromFormat( 'U', $timestamp );
        $entity = $this->getEntityManager()->getRepository(Dates::class)->findOneBy(['date' => $date]);

        if(!$entity){
            $entity = new Dates();
            $entity->setDate($date);
            $this->getEntityManager()->persist($entity);
            $this->getEntityManager()->flush();
        }

        return $entity;
    }

    protected function createItem($data){
        $item = new Items();
        $item->setItemId($data['mainKey']);
        $item->setName($data['name']);
        $this->getEntityManager()->persist($item);
        return $item;
    }

    protected function createItemPerDay(Items $item,Dates $date, $quantity) {
        $entity = new ItemsPerDay();
        $entity->setItem($item);
        $entity->setDate($date);
        $entity->setQuantity($quantity);
        $this->getEntityManager()->persist($entity);
        return $entity;
    }

    /**
     * @return  EntityManager
     */
    protected function getEntityManager(){
        return $this->getContainer()->get('doctrine.orm.entity_manager');
    }
}