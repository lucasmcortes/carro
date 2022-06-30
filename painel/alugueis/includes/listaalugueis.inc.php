<?php
        include_once __DIR__.'/../../../includes/setup.inc.php';
        $i=0;
        $r=0;
        $aan=0;
        $alugueis_anteriores = [];
        $alugueis_atuais = [];
        $alugueisativos = new ConsultaDatabase($uid);
        $alugueisativos = $alugueisativos->ListaAlugueis();
        if ($alugueisativos[0]['aid']!=0) {
                foreach ($alugueisativos as $aluguelativo) {
                        $devolucao = new ConsultaDatabase($uid);
                        $devolucao = $devolucao->Devolucao($aluguelativo['aid']);
                        if ($devolucao['deid']==0) {
                                $inicio = new DateTime($aluguelativo['inicio']);
                                $reserva = new ConsultaDatabase($uid);
                                $reserva = $reserva->Reserva($aluguelativo['aid']);
                                if ($reserva['reid']!=0) {
                                        $atividade = new ConsultaDatabase($uid);
                                        $atividade = $atividade->Ativacao($reserva['reid']);
                                        if ($atividade['ativa']=='S') {
                                                $inicio = new DateTime($reserva['inicio']);
                                        } // ativa
                                } // reserva
                                if ($inicio->format('Y-m-d H:i')>$agora->format('Y-m-d H:i')) {
                                        // existem reservas
                                        $reserva = new ConsultaDatabase($uid);
                                        $reserva = $reserva->Reserva($aluguelativo['aid']);

                                        $atividade = new ConsultaDatabase($uid);
                                        $atividade = $atividade->Ativacao($reserva['reid']);

                                        if ($atividade['ativa']=='S') {
                                                $r++;
                                        } // se é uma reserva ativa
                                }  else {
                                        $reserva = new ConsultaDatabase($uid);
                                        $reserva = $reserva->Reserva($aluguelativo['aid']);
                                        if ($reserva['reid']!=0) {
                                                $atividade = new ConsultaDatabase($uid);
                                                $atividade = $atividade->Ativacao($reserva['reid']);
                                                if ($atividade['ativa']=='S') {
                                                        $inicio_status = new DateTime($reserva['inicio']);
                                                        if ($inicio_status->format('Y-m-d H:i')<=$agora->format('Y-m-d H:i')) {
                                                                if ($reserva['confirmada']==1) {
                                                                        $i++;
                                                                        $alugueis_atuais[] = $aluguelativo;
                                                                } // se confirmou a reserva
                                                        }
                                                } // ativa
                                        } else {
                                                $i++;
                                                $alugueis_atuais[] = $aluguelativo;
                                        } // se foi reserva
                                } // datas
                        } else {
                                $aan++;
                                if (!in_array($aluguelativo,$alugueis_atuais)) {
                                        $alugueis_anteriores[] = $aluguelativo;
                                } // in array
                        }// devolucao == 0
                } // foreach
        } // ativos > 0
?>
