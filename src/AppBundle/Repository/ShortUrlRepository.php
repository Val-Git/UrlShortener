<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ShortUrlRepository extends EntityRepository
{
    public function cleanOldItems()
    {
        // находим дату 15 дней назад
        $date  = ( new \DateTime() )->sub(new \DateInterval('P15D'));

        // формируем выборку элементов
        $query = $this->createQueryBuilder('url')
                      ->delete()
                      ->where('url.created_at < :date')
                      ->setParameter('date', $date->format('Y-m-d H:i:s'))
                      ->getQuery();

        // удаляем найденное
        return $query->getResult();
    }

    public function isShortUrlExists($url)
    {
        if (empty($url)) {
            return false;
        }

        $item = $this->findOneBy(['short_url' => $url]);

        return ! empty($item);
    }

    public function idToStr($id)
    {
        $result = '';
        $chars  = str_shuffle('abcdefghijklmnopqrstuvwxyz0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
        $count  = strlen($chars);

        if ($id < pow($count, 5)) {
            $id += pow($count, 5);
        }

        for ($i = ($id != 0 ? floor(log($id, $count)) : 0); $i >= 0; $i--) {
            $bcp    = bcpow($count, $i);
            $a      = floor($id / $bcp) % $count;
            $result = $result . substr($chars, $a, 1);
            $id     = $id - ($a * $bcp);
        }

        return $result;
    }
}
