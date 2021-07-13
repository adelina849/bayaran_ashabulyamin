<?php

header("Content-type: application/octet-stream");

header("Content-Disposition: attachment; filename=laporan_neraca.xls");

header("Pragma: no-cache");

header("Expires: 0");

?>

<table table border="1" width="100%">
  <thead>
      <tr>
          <th width="50px">#</th>
           <th>Kode Transaksi</th>
           <th>Tanggal</th>
           <th>Nama Transaksi</th>
           <th>Keterangan</th>
           <th>Referensi</th>
           <th>Debit</th>
           <th>Kredit</th>
           <!-- <th>User</th> -->
      </tr>
  </thead>
  <tbody>
   <?php
     if(!empty($list_laporan))
     {
       $no=1;
       $total_debit=0;
       $total_kredit=0;

       foreach($list_laporan as $row)
       {
    ?>
         <tr>
           <td><?=$no;?></td>
           <td><?=$row->kode_tran;?></td>
           <td><?=$row->tgl_bayar;?></td>
           <td><?=$row->nama_transaksi;?></td>
           <td><?=$row->keterangan;?></td>
           <td><?=$row->no_ref;?></td>
           <td class="text-right"><?=$row->debit;?></td>
           <td class="text-right"><?=$row->kredit;?></td>
         </tr>
    <?php
         $no++;
         $total_debit += $row->debit;
         $total_kredit += $row->kredit;
       }
   ?>
   <tr>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td><strong>Total</strong></td>
     <td class="text-right"><strong><?=$total_debit;?></strong></td>
     <td class="text-right"><strong><?=$total_kredit;?></strong></td>
   </tr>
   <tr>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td><strong>Saldo</strong></td>
     <td class="text-right"><strong><?=$saldo_debit;?></strong></td>
     <td class="text-right"><strong><?=$saldo_kredit;?></strong></td>
   </tr>
   <tr>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td></td>
     <td><strong>Balance</strong></td>
     <td class="text-right"><strong><?=$total_debit+$saldo_debit;?></strong></td>
     <td class="text-right"><strong><?=$total_kredit+$saldo_kredit;?></strong></td>
   </tr>
 <?php
     }
    ?>
  </tbody>
  </table>
