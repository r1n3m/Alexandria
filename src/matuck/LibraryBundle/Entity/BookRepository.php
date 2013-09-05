<?php

namespace matuck\LibraryBundle\Entity;

use Doctrine\ORM\EntityRepository;
use matuck\LibraryBundle\Entity\Flags;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\DoctrineORMAdapter;


/**
 * BookRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class BookRepository extends EntityRepository
{
    public function findAll()
    {
        $qb = $this->createQueryBuilder('b')
               ->where('b.isPublic = 1')
               ->orderBy('b.title', 'ASC');
        
        return $qb->getQuery()->iterate();
    }
    
    public function findAllPagerOrderbyTitle()
    {
        $qb = $this->createQueryBuilder('b')
                ->where('b.isPublic = 1')
                ->orderBy('b.title', 'ASC');
        $adapter = new DoctrineORMAdapter($qb);
        
        return new Pagerfanta($adapter);
    }
    
    public function findByBookswithNumber()
    {
        $qb = $this->createQueryBuilder('b')
                ->where('b.isPublic = 1')
                ->orderBy('b.title', 'ASC');
        $qb->andWhere($qb->expr()->like('b.title', $qb->expr()->literal('0%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('1%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('2%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('3%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('4%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('5%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('6%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('7%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('8%')).' OR '.$qb->expr()->like('b.title', $qb->expr()->literal('9%')));
        $adapter = new DoctrineORMAdapter($qb);
        
        return new Pagerfanta($adapter);
    }
    
    public function findByFirstLetterPaged($letter)
    {
        $qb = $this->createQueryBuilder('b')
                ->where('b.isPublic = 1')
                ->andWhere('b.title LIKE :letter')
                ->orderBy('b.title', 'ASC')
                ->setParameter('letter', $letter.'%');
        
        $adapter = new DoctrineORMAdapter($qb);
        
        return new Pagerfanta($adapter);
    }
    
    public function findAllPagerOrderbyRating()
    {
        $qb = $this->createQueryBuilder('b')
                ->where('b.isPublic = 1')
                ->orderBy('b.rated', 'DESC');
        $adapter = new DoctrineORMAdapter($qb);
        
        return new Pagerfanta($adapter);
    }
    /**
     * 
     * @return int
     */
    public function totalbookcount()
    {
        $qb = $this->createQueryBuilder('b')
                ->select('count(b.id)')
                ->where('b.isPublic = 1');
        
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function newestbooks()
    {
        $qb = $this->createQueryBuilder('b')
                ->where('b.isPublic = 1')
                ->orderBy('b.id', 'desc');
        $adapter = new DoctrineORMAdapter($qb);
       
        return new Pagerfanta($adapter);
    }
    /**
     * 
     * @return \Pagerfanta\Pagerfanta
     */
    public function popularbooks()
    {
        $qb = $this->createQueryBuilder('b')
                ->select('b')
                ->where('b.isPublic = 1')
                ->groupBy('b.id')
                ->having('b.downcount > 0')
                ->orderBy('b.downcount', 'desc');
        $adapter = new DoctrineORMAdapter($qb);
        return new Pagerfanta($adapter);
    }
    
    public function findBySerie($serie, $ispublic = 1)
    {
        $qb = $this->createQueryBuilder('b')
                ->where('b.serie = :serie')
                ->andWhere('b.isPublic = :ispublic')
                ->orderBy('b.serieNbr', 'ASC')
                ->setParameter('serie', $serie)
                ->setParameter('ispublic', $ispublic);
        $adapter = new DoctrineORMAdapter($qb);
        return new Pagerfanta($adapter);
    }
    
    public function findByAuthor($author, $ispublic = 1)
    {
        $qb = $this->createQueryBuilder('b')
                ->where('b.author = :author')
                ->andWhere('b.isPublic = :ispublic')
                ->orderBy('b.serieNbr', 'ASC')
                ->setParameter('author', $author)
                ->setParameter('ispublic', $ispublic);
        $adapter = new DoctrineORMAdapter($qb);
        return new Pagerfanta($adapter);
    }
    
    public function getlastbookdate()
    {
        $qb = $this->createQueryBuilder('b')
    		->select('MAX(b.createdAt)');
        
        return $qb->getQuery()->getSingleScalarResult();
    }
    
    public function findBooksinArrayofIDs($idarray)
    {
        $qb = $this->createQueryBuilder('b');
        $qb->where($qb->expr()->in('b.id', $idarray));
        
        return $qb->getQuery()->getResult();
    }
    
    public function searchEverything($term)
    {
        $qb = $this->createQueryBuilder('b')
                ->leftJoin('b.serie', 's')
                ->leftJoin('b.author', 'a');
        $qb->Where($qb->expr()->like('b.title', $qb->expr()->literal('%'.$term.'%')).' OR '.$qb->expr()->like('a.name', $qb->expr()->literal('%'.$term.'%')).' OR '.$qb->expr()->like('s.name', $qb->expr()->literal('%'.$term.'%')));
        $qb->andWhere('b.isPublic = 1')
         ->orderBy('b.title', 'ASC');
        
        $adapter = new DoctrineORMAdapter($qb);
        return new Pagerfanta($adapter);
    }
}
