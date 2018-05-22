<script type="text/javascript" src="http://surat.uin-suka.ac.id/asset/js/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="http://surat.uin-suka.ac.id/asset/css/token-input.css" type="text/css" />
<script type="text/javascript">
 	$(document).ready(function() {
		$("#dosen").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_dosen");
		$("#mahasiswa").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_mahasiswa");
	}); 
</script>
<script type="text/javascript">
	$(function(){
		$("#addRow").click(function(){
			row = '<tr>'+
			'<td><input type="text" name="kpd_lainnya[]" class="input-grup input-xlarge" /></td>'+
			'<td><a class="btn btn-small btn-danger btn-act remove-btn" title="Hapus"><i class="icon-remove icon-white"></i></a></td>'+
			'</tr>';
			$(row).insertBefore("#last");
			x++;
		});
	});
	$(".remove-btn").on('click', function(){
		$(this).parent().last().remove();
	});
	
</script>
<script type="text/javascript">
	$(function(){ 
		$('.datepicker').datepicker({
			dateFormat : 'dd/mm/yy',
			buttonImage: 'http://akademik.uin-suka.ac.id/asset/img/calendar.jpg',
			showOn: "button",
			buttonImageOnly: true
		});
	});

	function hapus(msg){
		var conf = confirm(msg);
		if (conf == true) return true;
		else return false;
	}
	
	function autosum(){
		var sks1 = parseFloat(document.getElementById('sks1').value);
		var sks2 = parseFloat(document.getElementById('sks2').value);
		var capaian = (sks2/sks1)*100;
		document.getElementById('capaian').value = Math.round(capaian);
	}
	// FUNGSI AUTO
	function auto_fill(pre){
		var judul = $("#judul").val();
		var kegiatan = pre+' : '+judul;
		$("#kegiatan").val(kegiatan);
	}
</script>
<script>
$(document).ready(function(){
	$('#partner-penelitian-btn').click(function(){
		$('#partner-penelitian').fadeToggle();
	});
});
</script>
<style>
.grup{
	background-color: #EEEEEE;
}
.auto-surat{
	float: left;
	margin: 0px 0px 10px 0px;
	border: 1px solid #CCCCCC;
	border-radius: 4px;
	padding: 1px;
	width: 566px;
}
.label-input {
	width: 64px;
	float: left;
	padding:8px;
}
.tujuan-surat input:focus{
	box-shadow:none;
}
</style>
<div id="content">
<div>
	<div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/haki';?>" class="current">HAKI</a>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/haki/tambah';?>">Tambah Data</a>
		</ul><div style="clear:both"></div>
	</div>
	
		<!-- edit values -->
		<?php 
		if (!empty($curr_haki)){
			$val=$curr_haki;
			$kd_haki = $val->KD_HAKI;
			$judul = $val->JUDUL_HAKI;
			$no_sk = $val->NOMOR_SK;
			$tingkat = $val->TINGKAT; $tlabel = $tingkat;
			$tanggal_pub = date('d/m/Y', strtotime($val->TANGGAL_SK));
			$penerbit = $val->PENERBIT_SK;
			$akreditasi = $val->PEMILIK_HAK; $takre = $akreditasi;
			$label = 'Update';
			$action = 'update_data_haki';
		}else{
			$kd_haki = '';
			$judul = '';
			$no_sk = '';
			$tingkat = ''; $tlabel = '- Pilih tingkat -';
			$tanggal_pub = '';
			$penerbit = '';
			$akreditasi = ''; $takre = '- Pilih akreditasi -';
			$label = 'Simpan';
			$action = 'simpan_data_haki';
		}
		?>
		
		<!-- show form haki -->
		<h2>Form Data Hak Kekayaan Intelektual</h2>
		<?php echo form_open('bkd/dosen/bebankerja/'.$action);?>
			<?php echo validation_errors(); ?>
			<input type="hidden" name="kd_haki" value="<?php echo $kd_haki;?>">
			<table class="table table-condensd">
				<tr>
					<td>Judul</td>
					<td colspan="3"><textarea id="judul" name="judul" class="input-xxlarge" onblur="return auto_fill('HAKI')" required><?php echo $judul;?></textarea>
					</td>
				</tr>
				<tr>
					<td>Jenis HAKI</td>
					<td colspan="3"><select name="jenis_haki">
						<option value=""> - Jenis HAKI - </option>
						<?php foreach($jenis_haki as $jh):?>		
							<?php if(isset($val->KD_JENIS_HAKI) and $jh->KD_JENIS_HAKI==$val->KD_JENIS_HAKI):?>
							<option value="<?php echo $jh->KD_JENIS_HAKI?>" selected ><?php echo $jh->JENIS_HAKI?></option>
							<?php else:?>
							<option value="<?php echo $jh->KD_JENIS_HAKI?>" ><?php echo $jh->JENIS_HAKI?></option>
							<?php endif ?>
						<?php endforeach ?>
							<option value="99">LAINNYA</option>
						</select>
					<hr><div id="partner-penelitian-btn" class="link" style="cursor:pointer"><i class="icon-user"></i> Partner haki</div>
					</td>
				</tr>
				<tr id="partner-penelitian" style="display:none">
					<td>Partner haki</td>
					<td colspan="3">
						<div class="tujuan-surat">
							<div class="auto-surat grup">
								<div class="label-input">Dosen/Staff</div>
								<!-- cek nilai / edit position -->
								<input type="text" name="dosen" id="dosen"/>
							</div>
							<div class="auto-surat grup">
								<div class="label-input">Mahasiswa</div>
								<input type="text" name="mahasiswa" id="mahasiswa" />
							</div>
							<div class="auto-surat grup">
								<div class="label-input">Lainnya</div>
								<table>
									<tr>
										<td><input type="text" name="lain[]" class="input-grup" /></td>
										<td></td>
									</tr>
									<tr id="last"></tr>
									<tr><td colspan="2"><a class="btn btn-small" id="addRow" style="margin:0 0 5px 0">Tambah</a></td></tr>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<!-- end auto complete -->		
				<tr>
					<td>Tingkat</td>
					<td><select name="tingkat">
							<option value="<?php echo $tingkat;?>"><?php echo $tlabel;?></option>
							<option value="LOKAL">LOKAL</option>
							<option value="NASIONAL">NASIONAL</option>
							<option value="INTERNASIONAL">INTERNASIONAL</option>
						</select>
					</td>
				</tr>		
				<tr>
					<td>Nomor SK</td>
					<td colspan="3"><input type="text" name="nomor_sk" class="input-xxlarge" value="<?php echo $no_sk;?>" /></td>
				</tr>	
				<tr>					
					<td>Tanggal SK</td>
					<td><input type="text" name="tanggal_sk" class="datepicker input-medium" value="<?php echo $tanggal_pub;?>"></td>
				</tr>
				<tr>
					<td>Penerbit SK</td>
					<td colspan="3"><input type="text" name="penerbit" class="input-xxlarge" value="<?php echo $penerbit;?>"></td>
				</tr>
				<tr>
					<td>Pemilik Hak</td>
					<td colspan="3"><select name="pemilik_hak">
							<option value="<?php echo $akreditasi;?>"><?php echo $takre;?></option>
							<option value="Diri Sendiri">Diri Sendiri</option>
							<option value="Bersama Partner">Bersama Partner</option>
							<option value="LAIN">LAINNYA</option>
						</select>
					</td>
				</tr>			
				<?php 	
				$cur_data=array();
				if(isset($current_data))$cur_data=array('current_data'=>$current_data);
				$this->load->view('bkd/dosen/bkd_form',$cur_data);
				?>
				<tr>
					<td></td>
					<td><input type="submit" name="submit" value="<?php echo $label;?>" class="btn btn-uin btn-inverse btn-small">
						<input type="reset" name="reset" value="Batal" class="btn btn-uin btn-small black"></td>
				</tr>
			</table>
		<?php echo form_close();?>
	
	
		<!-- tampilkan beban kerja yang telah diambil -->
	    <h2>Data Beban Kerja Bidang Hak Kekayaan Intelektual</h2><h3>Tahun Akademik : <?php echo $this->session->userdata('ta');?>, Semester : 
		<?php echo $this->session->userdata('smt');?></h3>
        <table class="table table-bordered table-hover">
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Jenis Kegiatan</th>
                <th colspan="2">Beban Kerja</th>
                <th rowspan="2">Masa Penugasan</th>
                <th colspan="2">Kinerja</th>
                <th rowspan="2">Rekomendasi</th>
                <th colspan="2" rowspan="2" style="border-right:none">Aksi</th>
            </tr>
            <tr>
                <th>Bukti Penugasan</th>
                <th>SKS</th>
                <th>Bukti Dokumen</th>
                <th>SKS</th>
            </tr>
            <?php 
                if (empty($data_beban)){
                    echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>';
                }else{
                    $no=0; $sks_beban = 0; $sks_bukti = 0;
                    foreach ($data_beban as $bp){ $no++;
						$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
						$sks_bukti = $sks_bukti+$bp->SKS_BKT;
						if($this->uri->segment(6) !== ''){
							if($this->uri->segment(6) == $bp->KD_BK) $bg = '#FFFFDD'; else $bg='';
						}
					?>
                    <tr bgcolor="<?php echo $bg;?>">
                        <td><?php echo $no;?></td>
                        <td><?php echo $bp->JENIS_KEGIATAN;?></td>
                        <td><?php echo $bp->BKT_PENUGASAN;?></td>
                        <td align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
                        <td><?php echo $bp->MASA_PENUGASAN;?></td>
                        <td><?php echo $bp->BKT_DOKUMEN;?></td>
                        <td align="center"><?php echo (float)$bp->SKS_BKT;?></td>
                        <td><?php echo $bp->REKOMENDASI;?></td>
                        <td><a href="<?php echo site_url().'bkd/dosen/bebankerja/edit/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" class="btn btn-mini"><i class="icon icon-edit"></i> Edit</a></td>
                        <td><a onclick="return hapus('Anda yakin ingin menghapus data ini?')" href="<?php echo site_url().'bkd/dosen/bebankerja/hapus_hybrid/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" class="btn btn-mini">
                        <i class="icon icon-trash"></i> Hapus</a></td>
                    </tr>
                <?php } ?>
                <tr class="total">
                    <td></td>
                    <td colspan="2">Jumlah Beban Kerja</td>
                    <td align="center"><?php echo $sks_beban?></td>
                    <td colspan="2">Jumlah Kinerja</td>
                    <td align="center"><?php echo $sks_bukti;?></td>
                    <td colspan="4"></td>
                </tr>
            <?php } ?>
        </table>
	</div>
	
		
	<!-- form upload file penugasan dan -->
	<?php $this->load->view('dosen/isi_dokumen_rbkd');?>
	<?php $this->load->view('dosen/cari_dokumen_rbkd');?>
	<!-- end -->
</div>