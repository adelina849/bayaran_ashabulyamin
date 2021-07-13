<!doctype html>
<html>
    <head>
        <title>harviacode.com - codeigniter crud generator</title>
        <link rel="stylesheet" href="<?php echo base_url('assets/bootstrap/css/bootstrap.min.css') ?>"/>
        <style>
            body{
                padding: 15px;
            }
        </style>
    </head>
    <body>
        <h2 style="margin-top:0px">Tb_bank <?php echo $button ?></h2>
        <form action="<?php echo $action; ?>" method="post">
	    <div class="form-group">
            <label for="varchar">Norek <?php echo form_error('norek') ?></label>
            <input type="text" class="form-control" name="norek" id="norek" placeholder="Norek" value="<?php echo $norek; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Nama Bank <?php echo form_error('nama_bank') ?></label>
            <input type="text" class="form-control" name="nama_bank" id="nama_bank" placeholder="Nama Bank" value="<?php echo $nama_bank; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Atas Nama <?php echo form_error('atas_nama') ?></label>
            <input type="text" class="form-control" name="atas_nama" id="atas_nama" placeholder="Atas Nama" value="<?php echo $atas_nama; ?>" />
        </div>
	    <div class="form-group">
            <label for="varchar">Cabang <?php echo form_error('cabang') ?></label>
            <input type="text" class="form-control" name="cabang" id="cabang" placeholder="Cabang" value="<?php echo $cabang; ?>" />
        </div>
	    <div class="form-group">
            <label for="date">Tgl Pembuatan <?php echo form_error('tgl_pembuatan') ?></label>
            <input type="text" class="form-control" name="tgl_pembuatan" id="tgl_pembuatan" placeholder="Tgl Pembuatan" value="<?php echo $tgl_pembuatan; ?>" />
        </div>
	    <div class="form-group">
            <label for="ket_bank">Ket Bank <?php echo form_error('ket_bank') ?></label>
            <textarea class="form-control" rows="3" name="ket_bank" id="ket_bank" placeholder="Ket Bank"><?php echo $ket_bank; ?></textarea>
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Ins <?php echo form_error('tgl_ins') ?></label>
            <input type="text" class="form-control" name="tgl_ins" id="tgl_ins" placeholder="Tgl Ins" value="<?php echo $tgl_ins; ?>" />
        </div>
	    <div class="form-group">
            <label for="datetime">Tgl Updt <?php echo form_error('tgl_updt') ?></label>
            <input type="text" class="form-control" name="tgl_updt" id="tgl_updt" placeholder="Tgl Updt" value="<?php echo $tgl_updt; ?>" />
        </div>
	    <div class="form-group">
            <label for="int">User Updt <?php echo form_error('user_updt') ?></label>
            <input type="text" class="form-control" name="user_updt" id="user_updt" placeholder="User Updt" value="<?php echo $user_updt; ?>" />
        </div>
	    <input type="hidden" name="id_bank" value="<?php echo $id_bank; ?>" /> 
	    <button type="submit" class="btn btn-primary"><?php echo $button ?></button> 
	    <a href="<?php echo site_url('c_bank') ?>" class="btn btn-default">Cancel</a>
	</form>
    </body>
</html>