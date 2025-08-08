<?php

namespace App\Services;

use App\Models\SuricataAlert;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SuricataAnalysisService
{
    /**
     * Analisis komprehensif data Suricata
     */
    public function analyzeSecurityData(array $filters = []): array
    {
        $query = SuricataAlert::query();
        
        // Apply filters
        if (isset($filters['date_from'])) {
            $query->where('created_at', '>=', $filters['date_from']);
        }
        
        if (isset($filters['date_to'])) {
            $query->where('created_at', '<=', $filters['date_to']);
        }
        
        if (isset($filters['severity'])) {
            $query->where('priority', $filters['severity']);
        }
        
        $alerts = $query->orderBy('created_at', 'desc')->get();
        
        return [
            'summary' => $this->generateSecuritySummary($alerts),
            'threat_analysis' => $this->analyzeThreatPatterns($alerts),
            'network_analysis' => $this->analyzeNetworkTraffic($alerts),
            'timeline_analysis' => $this->analyzeTimeline($alerts),
            'risk_assessment' => $this->assessRiskLevel($alerts),
            'recommendations' => $this->generateRecommendations($alerts)
        ];
    }
    
    /**
     * Generate ringkasan keamanan
     */
    private function generateSecuritySummary(Collection $alerts): array
    {
        $total = $alerts->count();
        $high = $alerts->where('priority', 1)->count();
        $medium = $alerts->where('priority', 2)->count();
        $low = $alerts->where('priority', 3)->count();
        
        $today = $alerts->where('created_at', '>=', Carbon::today())->count();
        $yesterday = $alerts->whereBetween('created_at', [
            Carbon::yesterday()->startOfDay(),
            Carbon::yesterday()->endOfDay()
        ])->count();
        
        $trend = $today > $yesterday ? 'increasing' : ($today < $yesterday ? 'decreasing' : 'stable');
        $trendPercentage = $yesterday > 0 ? round((($today - $yesterday) / $yesterday) * 100, 2) : 0;
        
        return [
            'total_alerts' => $total,
            'high_priority' => $high,
            'medium_priority' => $medium,
            'low_priority' => $low,
            'today_alerts' => $today,
            'yesterday_alerts' => $yesterday,
            'trend' => $trend,
            'trend_percentage' => $trendPercentage,
            'average_per_day' => $this->calculateAveragePerDay($alerts)
        ];
    }
    
    /**
     * Analisis pola ancaman
     */
    private function analyzeThreatPatterns(Collection $alerts): array
    {
        // Kategorisasi ancaman berdasarkan message
        $threatCategories = [
            'malware' => ['malware', 'trojan', 'virus', 'worm', 'backdoor'],
            'intrusion' => ['intrusion', 'exploit', 'attack', 'penetration'],
            'ddos' => ['ddos', 'dos', 'flood', 'overload'],
            'suspicious' => ['suspicious', 'anomaly', 'unusual', 'abnormal'],
            'policy_violation' => ['policy', 'violation', 'unauthorized', 'forbidden']
        ];
        
        $categorized = [];
        foreach ($threatCategories as $category => $keywords) {
            $count = $alerts->filter(function ($alert) use ($keywords) {
                $message = strtolower($alert->message);
                return collect($keywords)->some(function ($keyword) use ($message) {
                    return strpos($message, $keyword) !== false;
                });
            })->count();
            
            $categorized[$category] = $count;
        }
        
        // Top attacking IPs
        $topAttackers = $alerts->groupBy('src_ip')
            ->map(function ($group) {
                return [
                    'ip' => $group->first()->src_ip,
                    'count' => $group->count(),
                    'severity_avg' => round($group->avg('priority'), 2),
                    'first_seen' => $group->min('created_at'),
                    'last_seen' => $group->max('created_at')
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->toArray();
        
        // Top targeted IPs
        $topTargets = $alerts->groupBy('dest_ip')
            ->map(function ($group) {
                return [
                    'ip' => $group->first()->dest_ip,
                    'count' => $group->count(),
                    'severity_avg' => round($group->avg('priority'), 2),
                    'attack_types' => $group->pluck('message')->unique()->take(5)->toArray()
                ];
            })
            ->sortByDesc('count')
            ->take(10)
            ->values()
            ->toArray();
        
        return [
            'threat_categories' => $categorized,
            'top_attackers' => $topAttackers,
            'top_targets' => $topTargets,
            'protocol_distribution' => $this->analyzeProtocolDistribution($alerts)
        ];
    }
    
    /**
     * Analisis traffic jaringan
     */
    private function analyzeNetworkTraffic(Collection $alerts): array
    {
        // Analisis berdasarkan subnet
        $internalIPs = $alerts->filter(function ($alert) {
            return $this->isInternalIP($alert->dest_ip);
        });
        
        $externalIPs = $alerts->filter(function ($alert) {
            return !$this->isInternalIP($alert->src_ip);
        });
        
        // Pola waktu serangan
        $hourlyPattern = $alerts->groupBy(function ($alert) {
            return Carbon::parse($alert->created_at)->format('H');
        })->map->count()->sortKeys()->toArray();
        
        $dailyPattern = $alerts->groupBy(function ($alert) {
            return Carbon::parse($alert->created_at)->format('Y-m-d');
        })->map->count()->sortKeys()->toArray();
        
        return [
            'internal_threats' => $internalIPs->count(),
            'external_threats' => $externalIPs->count(),
            'hourly_pattern' => $hourlyPattern,
            'daily_pattern' => $dailyPattern,
            'peak_hours' => $this->findPeakHours(collect($hourlyPattern)),
            'network_segments' => $this->analyzeNetworkSegments($alerts)
        ];
    }
    
    /**
     * Analisis timeline
     */
    private function analyzeTimeline(Collection $alerts): array
    {
        $timeline = $alerts->groupBy(function ($alert) {
            return Carbon::parse($alert->created_at)->format('Y-m-d H:00');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'high_severity' => $group->where('priority', 1)->count(),
                'avg_severity' => round($group->avg('priority'), 2)
            ];
        })->sortKeys()->toArray();
        
        return [
            'timeline' => $timeline,
            'incident_clusters' => $this->findIncidentClusters($alerts),
            'attack_duration' => $this->calculateAttackDuration($alerts)
        ];
    }
    
    /**
     * Penilaian tingkat risiko
     */
    private function assessRiskLevel(Collection $alerts): array
    {
        $recentAlerts = $alerts->where('created_at', '>=', Carbon::now()->subHours(24));
        $highSeverityRecent = $recentAlerts->where('priority', 1)->count();
        $totalRecent = $recentAlerts->count();
        
        $riskScore = 0;
        
        // Faktor risiko
        if ($highSeverityRecent > 10) $riskScore += 30;
        elseif ($highSeverityRecent > 5) $riskScore += 20;
        elseif ($highSeverityRecent > 0) $riskScore += 10;
        
        if ($totalRecent > 100) $riskScore += 25;
        elseif ($totalRecent > 50) $riskScore += 15;
        elseif ($totalRecent > 20) $riskScore += 10;
        
        // Unique attackers
        $uniqueAttackers = $recentAlerts->pluck('src_ip')->unique()->count();
        if ($uniqueAttackers > 20) $riskScore += 20;
        elseif ($uniqueAttackers > 10) $riskScore += 15;
        elseif ($uniqueAttackers > 5) $riskScore += 10;
        
        // Spread of targets
        $uniqueTargets = $recentAlerts->pluck('dest_ip')->unique()->count();
        if ($uniqueTargets > 10) $riskScore += 15;
        elseif ($uniqueTargets > 5) $riskScore += 10;
        
        // Determine risk level
        $riskLevel = 'Low';
        $riskColor = 'success';
        
        if ($riskScore >= 70) {
            $riskLevel = 'Critical';
            $riskColor = 'danger';
        } elseif ($riskScore >= 50) {
            $riskLevel = 'High';
            $riskColor = 'warning';
        } elseif ($riskScore >= 30) {
            $riskLevel = 'Medium';
            $riskColor = 'info';
        }
        
        return [
            'risk_score' => $riskScore,
            'risk_level' => $riskLevel,
            'risk_color' => $riskColor,
            'factors' => [
                'high_severity_alerts' => $highSeverityRecent,
                'total_recent_alerts' => $totalRecent,
                'unique_attackers' => $uniqueAttackers,
                'unique_targets' => $uniqueTargets
            ]
        ];
    }
    
    /**
     * Generate rekomendasi keamanan
     */
    private function generateRecommendations(Collection $alerts): array
    {
        $recommendations = [];
        
        $recentAlerts = $alerts->where('created_at', '>=', Carbon::now()->subHours(24));
        $highSeverity = $recentAlerts->where('priority', 1);
        
        // Rekomendasi berdasarkan high severity alerts
        if ($highSeverity->count() > 10) {
            $recommendations[] = [
                'type' => 'critical',
                'title' => 'Immediate Action Required',
                'description' => 'More than 10 high-severity alerts detected in the last 24 hours. Consider implementing emergency response procedures.',
                'action' => 'Review and block suspicious IP addresses immediately'
            ];
        }
        
        // Top attackers
        $topAttacker = $alerts->groupBy('src_ip')->sortByDesc(function ($group) {
            return $group->count();
        })->first();
        
        if ($topAttacker && $topAttacker->count() > 20) {
            $recommendations[] = [
                'type' => 'security',
                'title' => 'Block Persistent Attacker',
                'description' => "IP {$topAttacker->first()->src_ip} has generated {$topAttacker->count()} alerts. Consider blocking this IP.",
                'action' => 'Add to firewall blacklist'
            ];
        }
        
        // Protocol analysis
        $protocolStats = $this->analyzeProtocolDistribution($alerts);
        $topProtocol = collect($protocolStats)->sortByDesc('count')->first();
        
        if ($topProtocol && $topProtocol['count'] > ($alerts->count() * 0.6)) {
            $recommendations[] = [
                'type' => 'monitoring',
                'title' => 'Protocol-Specific Monitoring',
                'description' => "Most attacks are using {$topProtocol['protocol']} protocol. Enhance monitoring for this protocol.",
                'action' => 'Configure additional rules for ' . $topProtocol['protocol']
            ];
        }
        
        // Time-based recommendations
        $peakHours = $this->findPeakHours($alerts->groupBy(function ($alert) {
            return Carbon::parse($alert->created_at)->format('H');
        })->map->count());
        
        if ($peakHours) {
            $recommendations[] = [
                'type' => 'operational',
                'title' => 'Peak Hours Security',
                'description' => "Most attacks occur during hours: " . implode(', ', $peakHours) . ". Increase monitoring during these times.",
                'action' => 'Schedule additional security personnel during peak hours'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Helper methods
     */
    private function isInternalIP(string $ip): bool
    {
        $internalRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16'
        ];
        
        foreach ($internalRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                return true;
            }
        }
        
        return false;
    }
    
    private function ipInRange(string $ip, string $range): bool
    {
        list($subnet, $bits) = explode('/', $range);
        $ip = ip2long($ip);
        $subnet = ip2long($subnet);
        $mask = -1 << (32 - $bits);
        $subnet &= $mask;
        return ($ip & $mask) == $subnet;
    }
    
    private function analyzeProtocolDistribution(Collection $alerts): array
    {
        $totalAlerts = $alerts->count();
        return $alerts->groupBy('protocol')
            ->map(function ($group, $protocol) use ($totalAlerts) {
                return [
                    'protocol' => $protocol,
                    'count' => $group->count(),
                    'percentage' => round(($group->count() / $totalAlerts) * 100, 2)
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->toArray();
    }
    
    private function findPeakHours(Collection $hourlyData): array
    {
        $max = $hourlyData->max();
        $threshold = $max * 0.8; // 80% of peak
        
        return $hourlyData->filter(function ($count) use ($threshold) {
            return $count >= $threshold;
        })->keys()->toArray();
    }
    
    private function analyzeNetworkSegments(Collection $alerts): array
    {
        $segments = $alerts->map(function ($alert) {
            return [
                'src_segment' => $this->getNetworkSegment($alert->src_ip),
                'dest_segment' => $this->getNetworkSegment($alert->dest_ip)
            ];
        });
        
        return $segments->groupBy('dest_segment')
            ->map(function ($group, $segment) {
                return [
                    'segment' => $segment,
                    'attack_count' => $group->count(),
                    'unique_attackers' => $group->pluck('src_segment')->unique()->count()
                ];
            })
            ->sortByDesc('attack_count')
            ->take(10)
            ->values()
            ->toArray();
    }
    
    private function getNetworkSegment(string $ip): string
    {
        $parts = explode('.', $ip);
        return $parts[0] . '.' . $parts[1] . '.' . $parts[2] . '.0/24';
    }
    
    private function findIncidentClusters(Collection $alerts): array
    {
        $clusters = [];
        $threshold = 5; // Minimum alerts in 1 hour to be considered a cluster
        
        $hourlyGroups = $alerts->groupBy(function ($alert) {
            return Carbon::parse($alert->created_at)->format('Y-m-d H:00');
        });
        
        foreach ($hourlyGroups as $hour => $group) {
            if ($group->count() >= $threshold) {
                $clusters[] = [
                    'time' => $hour,
                    'alert_count' => $group->count(),
                    'severity_avg' => round($group->avg('priority'), 2),
                    'unique_sources' => $group->pluck('src_ip')->unique()->count()
                ];
            }
        }
        
        return collect($clusters)->sortByDesc('alert_count')->take(10)->values()->toArray();
    }
    
    private function calculateAttackDuration(Collection $alerts): array
    {
        $attackSessions = $alerts->groupBy('src_ip')
            ->map(function ($group) {
                $start = Carbon::parse($group->min('created_at'));
                $end = Carbon::parse($group->max('created_at'));
                $duration = $end->diffInMinutes($start);
                
                return [
                    'ip' => $group->first()->src_ip,
                    'duration_minutes' => $duration,
                    'alert_count' => $group->count(),
                    'persistence_score' => $duration > 0 ? $group->count() / $duration : $group->count()
                ];
            })
            ->where('duration_minutes', '>', 0)
            ->sortByDesc('persistence_score')
            ->take(10)
            ->values();
        
        return $attackSessions->toArray();
    }
    
    private function calculateAveragePerDay(Collection $alerts): float
    {
        if ($alerts->isEmpty()) return 0;
        
        $firstAlert = Carbon::parse($alerts->min('created_at'));
        $lastAlert = Carbon::parse($alerts->max('created_at'));
        $days = $firstAlert->diffInDays($lastAlert) + 1;
        
        return round($alerts->count() / $days, 2);
    }
}
