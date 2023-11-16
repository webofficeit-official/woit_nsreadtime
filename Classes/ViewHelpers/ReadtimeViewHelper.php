<?php

declare(strict_types=1);

namespace Woit\WoitNsreadtime\ViewHelpers;

use Closure;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Utility\DebuggerUtility;
use Typo3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithRenderStatic;

final class ReadtimeViewHelper extends AbstractViewHelper {
    use CompileWithRenderStatic;
    
    public $escapeOutput = false;

    public function initializeArguments()
    {
        $this->registerArgument('newsId', 'int', 'News id for check read time', true);
    }

    public static function renderStatic(array $arguments, Closure $renderChildrenClosure, RenderingContextInterface $renderingContext)
    {
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tx_news_domain_model_news');
        $data = $queryBuilder
            ->select('*')
            ->from('tx_news_domain_model_news')
            ->where(
                $queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($arguments['newsId']))
            )
            ->executeQuery()
            ->fetchAssociative();
            
        $word = str_word_count(strip_tags($data['bodytext']));

        $contentElementQueryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)->getQueryBuilderForTable('tt_content');
        $contentElements = $contentElementQueryBuilder
            ->select('*')
            ->from('tt_content')
            ->orWhere(
                $contentElementQueryBuilder->expr()->eq('CType', $contentElementQueryBuilder->createNamedParameter('text')),
                $contentElementQueryBuilder->expr()->eq('CType', $contentElementQueryBuilder->createNamedParameter('textpic')),
                $contentElementQueryBuilder->expr()->eq('CType', $contentElementQueryBuilder->createNamedParameter('textmedia'))
            )
            ->andWhere(
                $contentElementQueryBuilder->expr()->eq('tx_news_related_news', $contentElementQueryBuilder->createNamedParameter($arguments['newsId']))
            )
            ->executeQuery()
            ->fetchAllAssociative();

        foreach($contentElements as $elements) {
            $word += str_word_count(strip_tags($elements['bodytext']));
        }

        $minutes = floor($word / 200);
        $seconds = floor($word % 200 / (200 / 60));
        $hours = floor($minutes / 60);
        $minutes %= 60;

        if($minutes == 00 && $hours == 00) {
            $time = sprintf('%02d', $seconds) . ' seconds';
        }
        else if($hours == 00) {
            $time = sprintf('%02d:%02d', $minutes, $seconds) . ' minutes' ;
        } 
        else {
            $time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) . ' hours';
        }

        return $time;
    }
}