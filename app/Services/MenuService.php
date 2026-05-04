<?php

namespace App\Services;

class MenuService
{
    protected static array $superadmin = [
        'sep' => ['route' => null, 'caption' => 'Main Menu'],
        'super' => ['route' => 'admin', 'caption' => 'Home', 'icon' => 'bi-grid-1x2-fill'],
        'user' => ['route' => 'user.index', 'caption' => 'User', 'icon' => 'bi-people-fill'],
        'wilayah' => ['route' => 'wilayah.index', 'caption' => 'Wilayah', 'icon' => 'bi-map-fill'],
        'indikator' => ['route' => 'indikator.index', 'caption' => 'Indikator', 'icon' => 'bi-folder-fill'],
        'pelaksana' => ['route' => 'pelaksana.index', 'caption' => 'Pelaksana', 'icon' => 'bi-building-fill'],
        'sektor_terdampak' => ['route' => 'sektor_terdampak.index', 'caption' => 'Dampak Bencana', 'icon' => 'bi-heart-pulse-fill'],
        'perbaikan' => ['route' => 'perbaikan.index', 'caption' => 'Perbaikan', 'icon' => 'bi-tools'],
        'masalah_kritis' => ['route' => 'masalah_kritis.index', 'caption' => 'Masalah Kritis', 'icon' => 'bi-info-circle-fill'],
        'kategori_paket_pekerjaan' => ['route' => 'kategori_paket_pekerjaan.index', 'caption' => 'Kategori Paket Pekerjaan', 'icon' => 'bi-briefcase-fill'],
        'paket_pekerjaan' => ['route' => 'paket_pekerjaan.index', 'caption' => 'Paket Pekerjaan', 'icon' => 'bi-briefcase-fill'],
        'anggaran_daerah' => ['route' => 'anggaran_daerah.index', 'caption' => 'Anggaran Daerah', 'icon' => 'bi-cash'],
        'alokasi_anggaran' => ['route' => 'alokasi_anggaran.index', 'caption' => 'Alokasi Anggaran', 'icon' => 'bi-cash'],
        'penyedia' => ['route' => 'penyedia.index', 'caption' => 'Penyedia', 'icon' => 'bi-person'],
    ];

    protected static array $dalrenduk = [
        'sep' => ['route' => null, 'caption' => 'Main Menu'],
        'super' => ['route' => 'admin', 'caption' => 'Home', 'icon' => 'bi-grid-1x2-fill'],
        'kategori_paket_pekerjaan' => ['route' => 'kategori_paket_pekerjaan.index', 'caption' => 'Kategori Paket Pekerjaan', 'icon' => 'bi-briefcase-fill'],
        'paket_pekerjaan' => ['route' => 'paket_pekerjaan.index', 'caption' => 'Paket Pekerjaan', 'icon' => 'bi-briefcase-fill'],
        'anggaran_daerah' => ['route' => 'anggaran_daerah.index', 'caption' => 'Anggaran Daerah', 'icon' => 'bi-cash'],
        'alokasi_anggaran' => ['route' => 'alokasi_anggaran.index', 'caption' => 'Alokasi Anggaran', 'icon' => 'bi-cash'],
        'penyedia' => ['route' => 'penyedia.index', 'caption' => 'Penyedia', 'icon' => 'bi-person'],
    ];

    public function list_menu($role): array
    {
        return match ($role) {
            'Super Admin' => self::$superadmin,
            'Dalrenduk' => self::$dalrenduk,
            default => [],
        };
    }

    public static function current_menu($menus, $current_route, $role_active, $current_route_params = []) {
        $breadcrumbs = [['route' => head(explode('.', $current_route)), 'caption' => $role_active]];

        $current_menu = [];
        $current_sub_menu = [];
        $current_side_menu = [];
        foreach ($menus as $menu) {
            if ($menu['route'] === $current_route && ($menu['params'] ?? []) === $current_route_params) {
                $current_menu = $menu;
                $breadcrumbs[] = $menu;
            }
            foreach ($menu['sub_menus'] ?? [] as $sub_menu) {
                if ($sub_menu['route'] === $current_route && ($sub_menu['params'] ?? []) === $current_route_params) {
                    $current_menu = $menu;
                    $current_sub_menu = $sub_menu;
                    if ($sub_menu['route'] !== $menu['route']) $breadcrumbs[] = $sub_menu;
                }
                foreach ($sub_menu['side_menus'] ?? [] as $side_menu) {
                    if ($side_menu['route'] === $current_route && ($side_menu['params'] ?? []) === $current_route_params) {
                        $current_menu = $menu;
                        $current_sub_menu = $sub_menu;
                        $current_side_menu = $side_menu;
                        $breadcrumbs[] = $sub_menu;
                        $breadcrumbs[] = $side_menu;
                    }
                }
            }
            foreach ($menu['side_menus'] ?? [] as $side_menu) {
                if ($side_menu['route'] === $current_route && ($side_menu['params'] ?? []) === $current_route_params) {
                    $current_menu = $menu;
                    $current_side_menu = $side_menu;
                    if (last($breadcrumbs)['route'] !== $menu['route']) $breadcrumbs[] = $menu;
                    if ($side_menu['route'] !== $menu['route'] || ($side_menu['params'] ?? []) !== ($menu['params'] ?? [])) $breadcrumbs[] = $side_menu;
                }
            }
        }

        if (empty($current_menu)) {
            $temp = explode('.', $current_route);
            if (last($temp) === 'show' || last($temp) === 'create') {
                $temp[count($temp) - 1] = 'index';
                $current_route = join('.', $temp);
                return self::current_menu($menus, $current_route, $role_active, $current_route_params);
            } else {
                if (count($temp) > 2) {
                    array_splice($temp, count($temp) - 2, 1);
                    $current_route = join('.', $temp);
                    return self::current_menu($menus, $current_route, $role_active, $current_route_params);
                }
            }
        }

        $current = $current_side_menu ?? [];
        if (empty($current)) $current = $current_sub_menu ?? [];
        if (empty($current)) $current = $current_menu;
        $actions = $current['actions'] ?? [];


        return [
            'current_menu' => $current_menu,
            'current_sub_menu' => $current_sub_menu,
            'current_side_menu' => $current_side_menu,
            'breadcrumbs' => $breadcrumbs,
            'actions' => $actions,
        ];
    }

}
