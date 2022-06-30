<?php
include_once __DIR__.'/../../../includes/setup.inc.php';
$reservas_anteriores = [];
$confirmar = [];
$reservas_hoje = [];
$reservas_futuras = [];
$reservas_canceladas = [];
$reservas = new ConsultaDatabase($uid);
$reservas = $reservas->ListaReservas();
if ($reservas[0]['reid']!=0) {
        foreach ($reservas as $reserva) {
                $aid = $reserva['aid'];
                $reserva = new ConsultaDatabase($uid);
                $reserva = $reserva->Reserva($aid);
                if ($reserva['reid']!=0) {
                        $atividade = new ConsultaDatabase($uid);
                        $atividade = $atividade->Ativacao($reserva['reid']);
                        if ($atividade['atid']!=0) {
                                $inicio = new DateTime($reserva['inicio']);
                                $devolucao = new DateTime($reserva['devolucao']);
                                if ($inicio->format('Y-m-d')==$agora->format('Y-m-d')) {
                                        if ($atividade['ativa']=='N') {
                                                $reservas_canceladas[] = $reserva;
                                        } else if ($atividade['ativa']=='S') {
                                                if ($inicio->format('H')>$agora->format('H')) {
                                                        if (!in_array($reserva,$reservas_canceladas)) {
                                                                $reservas_hoje[] = $reserva;
                                                        } // não é uma reserva cancelada
                                                } else {
                                                        if ($reserva['confirmada']==0) {
                                                                $confirmar[] = $aid;
                                                        } // confirmada
                                                } // hora
                                        } // ativa
                                } else if ($inicio->format('Y-m-d')>$agora->format('Y-m-d')) {
                                        if ($atividade['ativa']=='N') {
                                                $reservas_canceladas[] = $reserva;
                                        } else if ($atividade['ativa']=='S') {
                                                if (!in_array($reserva,$reservas_canceladas)) {
                                                        $reservas_futuras[] = $reserva;
                                                } // não é uma reserva cancelada
                                        } // ativa
                                } else if ($inicio->format('Y-m-d')<$agora->format('Y-m-d')) {
                                        if ($atividade['ativa']=='N') {
                                                $reservas_canceladas[] = $reserva;
                                        } else if ($atividade['ativa']=='S') {
                                                if (!in_array($reserva,$reservas_canceladas)) {
                                                        $reservas_anteriores[] = $reserva;
                                                } // não é uma reserva cancelada
                                        } // canceladas
                                } // datas
                        } // atid != 0
                } // reid != 0
        } // foreach
} // reservas ativas > 0

// arruma
$reservas_canceladas = array_unique($reservas_canceladas, SORT_REGULAR);
?>
