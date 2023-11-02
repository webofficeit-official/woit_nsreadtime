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
        $this->registerArgument('format', 'boolean', 'Type of return value as 00:00:00', false);
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
            ->execute()
            ->fetchAssociative();
            
        $word = str_word_count(strip_tags($data['bodytext']));
        $minutes = floor($word / 200);
        $seconds = floor($word % 200 / (200 / 60));
        $hours = floor($minutes / 60);
        $minutes %= 60;


        $time = ($hours == 00 ? '' : $hours . ' hour' . ($hours == 1 ? '' : 's') . ', ') . ($minutes == 00 ? '' : $minutes . ' minute' . ($minutes == 1 ? '' : 's') . ', ') . ($seconds == 00 ? '' : $seconds . ' second' . ($seconds == 1 ? '' : 's'));

        if($arguments['format']) {
            if($minutes == 00 && $hours == 00) {
                $time = sprintf('%02d', $seconds) . ' seconds';
            }
            else if($hours == 00) {
                $time = sprintf('%02d:%02d', $minutes, $seconds) . ' minutes' ;
            } 
            else {
                $time = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds) . ' hours';
            }
        }

        return $time;
    }
}