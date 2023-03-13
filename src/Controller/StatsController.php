<?php

namespace App\Controller;

use App\Entity\Decision;
use App\Repository\DecisionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Chartjs\Builder\ChartBuilderInterface;
use Symfony\UX\Chartjs\Model\Chart;

class StatsController extends AbstractController
{
    #[Route('/stats', name: 'app_stats')]
    public function index(ChartBuilderInterface $chartBuilder, DecisionRepository $decisionRepository): Response
    {
        $chart = $this->multipleChartPie($decisionRepository, $chartBuilder);
        $chart1 = $this->chartPie($chartBuilder, $decisionRepository);
        $chart2 = $this->barChart($chartBuilder, $decisionRepository);

        return $this->render('stats/index.html.twig', [
            'multiplePieCart' => $chart,
            'pieChart' => $chart1,
            'barChart' => $chart2
        ]);
    }

    public function chartPie(ChartBuilderInterface $chartBuilder, DecisionRepository $decisionRepository): Chart
    {
        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $count = $decisionRepository->countHowManyDecisionsIn30Days();
        $countForData = [];
        foreach ($count as $value) {
            foreach ($value as $val) {
                foreach ($val as $v) {
                    $countForData[] = $v;
                }
            }
        }
        $chart->setData([
//            'labels' => [
//                'Decision started',
//                '1st decision',
//                'Decision in conflict',
//                'Final decision',
//                'Decision cancelled',
//                'Decision taken',
//            ],
            'datasets' => [
                [
//                    'label' => 'Decisions in 30 days',
                    'backgroundColor' => [
                        'rgb(12,57,68)',
                        'rgb(243,151,107)',
                        'rgb(252,246,160)',
                        'rgb(198,46,67)',
                        'rgb(193,233,78)',
                        'rgb(155,8,79)'
                    ],
                    //'borderColor' => 'black',
                    'data' => $countForData,
                ],
            ],
        ]);
        return $chart;
    }

    public function multipleChartPie(DecisionRepository $decisionRepository, ChartBuilderInterface $chartBuilder): Chart
    {
        $count = $decisionRepository->countHowManyDecisionsIn30Days();
        $countForData = [];
        foreach ($count as $value) {
            foreach ($value as $val) {
                foreach ($val as $v) {
                    $countForData[] = $v;
                }
            }
        }

        $chart = $chartBuilder->createChart(Chart::TYPE_PIE);
        $totalAllDecisions = array_sum($countForData);

        $allWout1stDecision = array_slice($countForData, 1);
        $without1stDecision = array_sum($allWout1stDecision);
        $pct1stDecision = $without1stDecision * 100 / $totalAllDecisions;

        $allWthoutConflict = array_slice($allWout1stDecision, 1);
        $sum1 = array_sum($allWthoutConflict);
        $pctDecisionConflict = $allWthoutConflict[0] * 100 / $totalAllDecisions;
        $pctDecisionFinal = $allWthoutConflict[1] * 100 / $totalAllDecisions;

        $aboutieCancel = array_slice($allWthoutConflict, 2);
        $sum = array_sum($aboutieCancel);
        $pctNonAboutie = $aboutieCancel[0] * 100 / $totalAllDecisions;
        $pctDecisionFinished = $aboutieCancel[1] * 100 / $totalAllDecisions;

        $chart->setData([
            'labels' => [
            ],
            'datasets' => [
                [
                    'backgroundColor' => ['rgb(12,57,68)', 'black'],
                    'data' => [100, 0]
                ],
                [
                    'backgroundColor' => ['white', 'rgb(243,151,107)'],
                    'data' => [100 - $pct1stDecision, $pct1stDecision]
                ],
                [
                    'backgroundColor' => ['white', 'rgb(252,246,160)', 'rgb(198,46,67)'],
                    'data' => [100 - $sum1, $pctDecisionConflict, $pctDecisionFinal]
                ],
                [
                    'backgroundColor' => ['white', 'rgb(193,233,78)', 'rgb(155,8,79)'],
                    'data' => [100 - $sum, $pctNonAboutie, $pctDecisionFinished]
                ],
            ],
        ]);
        return $chart;
    }

    public function barChart(ChartBuilderInterface $chartBuilder, DecisionRepository $decisionRepository): Chart
    {
        $count = $decisionRepository->decisionByTrimester();
        $countForData = [];
        foreach ($count as $value) {
            foreach ($value as $val) {
                foreach ($val as $v) {
                    $countForData[] = $v;
                }
            }
        }
        $chart = $chartBuilder->createChart(Chart::TYPE_BAR);

        $chart->setData([
            'labels' => ['1st trimester', '2nd trimester', '3rd trimester', '4th trimester'],
            'datasets' => [
                [
                    'label' => 'Decisions by trimester',
                    'backgroundColor' => 'rgb(12,57,68)',
                    'data' => $countForData
                ],
            ]
        ]);
        return $chart;
    }
}
