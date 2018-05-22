
<script type="text/javascript" src="http://surat.uin-suka.ac.id/asset/js/jquery.tokeninput.js"></script>
<!-- <script type='text/javascript' src="http://akademik.uin-suka.ac.id/asset/js2/jquery-1.8.2.min.js"></script> -->
 <script type='text/javascript' src="http://akademik.uin-suka.ac.id/asset/js2/jquery.autocomplete.js"></script>
 <script type='text/javascript' src="http://akademik.uin-suka.ac.id/asset/js_select2/select2.min.js"></script>
  <link href="http://akademik.uin-suka.ac.id/asset/js2/jquery.autocomplete.css" rel='stylesheet' />
  
  <link href="http://akademik.uin-suka.ac.id/asset/css_select2/select2.min.css" rel='stylesheet' />
<!-- <script type='text/javascript' src="http://akademik.uin-suka.ac.id/asset/js/jquery.autocomplete.min.js"></script> -->
<!-- <script type="text/javascript">
 	$(document).ready(function(){
		$("#daftar").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/remun/search");
	}); 
</script> -->
<link rel="stylesheet" href="http://surat.uin-suka.ac.id/asset/css/token-input.css" type="text/css" />
<script type="text/javascript">
 	$(document).ready(function() {
		$("#dosen").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_dosen");
		$("#mahasiswa").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_mahasiswa");
	});
	/*$(document).ready(function(){
		$("#coba").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/remun/get_surat");
	});*/ 
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
		if(capaian > 100){
			capaian = 100;
		}
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
 <script type="text/javascript">
            $(document).ready(function() {
                $("#kategori").select2({
                });
               /* if(isset(<?php echo $current_data;?>)){
                	$("#kategori").prop("disabled", true);
                	//$('#kategori').select2('disable');
                }*/
                $('#kategori').on("select2:select", function(e) {
                    uji_coba();
                    var kodex = $('#kategori :selected').val();
                    $.ajax({
                    url : '<?php echo base_url('bkd/dosen/remun/get_spesifik_kategori')?>',
                    type : 'POST',
                    data : 'kode='+kodex,
                    success:function(result){
                        console.log(result);
                        var a = JSON.parse(result);
                        $('#kd_kat').val(a.KD_KAT);
                        $('#satuan').val(a.SATUAN);
                        $('#nilai_kat').val(a.NILAI_KAT);

                        var satuan = a.SATUAN;
                        var nilai_kat = a.NILAI_KAT;

                        function default_data(){
	                        var data = new Array("$('#jml_sks').val('0');", "$('#jml_tatap_muka').val('0');", "$('#pertemuan_pm').val('0');", "$('#prodi').val('');", "$('#capaian').val('');","$('#jenjang').val('');", "$('#jenis_kelas').val('');", "$('#jml_mhs').val('0');","$('#lama').val('1');", "$('#masa').val('Semester');", "$('#idrekomendasi').val('');", "$('#tempat').val('');");
	                        var get_dev = data;
	                        var length_dev = data.length;
	                        var def;
	                        for(var y=0; y<length_dev; y++){
	                            def= eval(get_dev[y]);
	                        }
	                        return def;
                    	}
	                    function jenis_jumlah(){
	                        var jenis_satuan = satuan;
	                        switch(jenis_satuan){
	                            case 'SKS':
	                                jenis_satuan = 'MHS';
	                                break;
	                            case 'JUMLAH':
	                                jenis_satuan = jenis_satuan;
	                                break;
	                            default:
	                                break;
	                        }
	                        var data = document.getElementById("label_jumlah").innerText = "Jumlah "+jenis_satuan;
	                        return data;
	                    }
	                    var nilai_kategori = nilai_kat;
	                    switch(nilai_kategori){
	                        case 'SKS':
	                            jenis_jumlah();
	                            default_data();
	                            $("#row_pertemuan").show();$("#label_jumlah_sks").show();$("#input_jumlah_sks").show();$("#td_kelas").attr('colspan',0);
	                            break;
	                        case 'JUMLAH':
	                            jenis_jumlah();
	                            default_data();
	                            $("#row_pertemuan").hide();$("#label_jumlah_sks").hide();$("#input_jumlah_sks").hide();$("#td_kelas").attr('colspan',3);
	                            break;
	                        default:
	                            break;
	                    }
                        
                    }
                })  
                });
            });
 </script>
<script type="text/javascript">
            function uji_coba(){
                var value = $('#kategori').val();
                $.ajax({
                    url : '<?php echo base_url('bkd/dosen/remun/get_spesifik_kategori')?>',
                    type : 'POST',
                    data : 'kode='+value,
                    success:function(result){
                        console.log(result);
                        var a = JSON.parse(result);
                        $('#kd_kat').val(a.KD_KAT);
                        $('#satuan').val(a.SATUAN);
                        $('#nilai_kat').val(a.NILAI_KAT);
                    }
                })
            }
 </script>
<!-- TF -->
<script type='text/javascript'>
        var site = "http://akademik.uin-suka.ac.id/bkd/dosen/";
        $(function(){
            $('.autocompleteA').autocomplete({
                // serviceUrl berisi URL ke controller/fungsi yang menangani request kita
                serviceUrl: site+'/remun/search2/',
                // fungsi ini akan dijalankan ketika user memilih salah satu hasil request
                onSelect: function (suggestion) {
                    $('#kd_kat').val(''+suggestion.nilai); // membuat id 'v_nim' untuk ditampilkan
                    $('#satuan').val(''+suggestion.satuan);
                    
                    function default_data(){
                        var data = new Array("$('#jml_sks').val('0');", "$('#jml_tatap_muka').val('0');", "$('#pertemuan_pm').val('0');", "$('#prodi').val('');", "$('#capaian').val('');","$('#jenjang').val('');", "$('#jenis_kelas').val('');", "$('#jml_mhs').val('0');","$('#lama').val(''+suggestion.set_masa_tugas);", "$('#masa').val(''+suggestion.set_rincian_masa);", "$('#idrekomendasi').val('');", "$('#tempat').val('');");
                        var get_dev = data;
                        var length_dev = data.length;
                        var def;
                        for(var y=0; y<length_dev; y++){
                            def= eval(get_dev[y]);
                        }
                        return def;
                    }
                    function jenis_jumlah(){
                        var jenis_satuan = suggestion.satuan;
                        switch(jenis_satuan){
                            case 'SKS':
                                jenis_satuan = 'MHS';
                                break;
                            case 'JUMLAH':
                                jenis_satuan = jenis_satuan;
                                break;
                            default:
                                break;
                        }
                        var data = document.getElementById("label_jumlah").innerText = "Jumlah "+jenis_satuan;
                        return data;
                    }
                    var nilai_kategori = suggestion.nilai_kat;
                    switch(nilai_kategori){
                        case 'SKS':
                            jenis_jumlah();
                            default_data();
                            $("#row_pertemuan").show();
                             $("#label_jumlah_sks").show();
                            $("#input_jumlah_sks").show();
                             $("#td_kelas").attr('colspan',0);
                            break;
                        case 'JUMLAH':
                            jenis_jumlah();
                            default_data();
                            $("#row_pertemuan").hide();
                            $("#label_jumlah_sks").hide();
                            $("#input_jumlah_sks").hide();
                             $("#td_kelas").attr('colspan',3);
                            break;
                        default:
                            break;
                    }
                }
            });
        });
        $(function(){
            $('.autocompleteB').autocomplete({
                // serviceUrl berisi URL ke controller/fungsi yang menangani request kita
                serviceUrl: site+'/remun/search3/',
                // fungsi ini akan dijalankan ketika user memilih salah satu hasil request
                onSelect: function (suggestion) {
                    $('#kd_kat').val(''+suggestion.nilai); // membuat id 'v_nim' untuk ditampilkan
                }
            });
        });
        $(function(){
            $('.autocompleteD').autocomplete({
                // serviceUrl berisi URL ke controller/fungsi yang menangani request kita
                serviceUrl: site+'/remun/search4/',
                // fungsi ini akan dijalankan ketika user memilih salah satu hasil request
                onSelect: function (suggestion) {
                    $('#kd_kat').val(''+suggestion.nilai); // membuat id 'v_nim' untuk ditampilkan
                }
            });
        });
        $(document).ready(function() {
                /*var country = ["Australia", "Bangladesh", "Denmark", "Hong Kong", "Indonesia", "Netherlands", "New Zealand", "South Africa"];*/
                var prodi = <?php echo $name_prodi;?>;
                $("#prodi").select2({
                  data: prodi
                });
            });
    </script>
<!-- TF -->
<!-- mekanisme show dan hide element form input -->

<!-- TF -->
<!-- <script type="text/javascript">
	window.onload=function(){
        document.getElementById("label_jumlah").innerText = "Jumlah MHS";
	}
</script> -->

<?php
	if($kode=='A'){?>
		<script type="text/javascript">
			window.onload=function(){
		        document.getElementById("label_jumlah").innerText = "Jumlah MHS";
		        $("#tr_beban_penugasan").hide();
		        $("#tr_sks_beban_penugasan").hide();
		        $("#tr_bukti_kinerja").hide();
		        $("#tr_sks_bukti_kinerja").hide();
		        $("#tr_capaian").hide();
			}
		</script>
	<?php }elseif($kode == 'B'){?>
		<script type="text/javascript">
			window.onload=function(){
		        document.getElementById("label_jumlah").innerText = "Jumlah Kegiatan";
		         $("#tr_beban_penugasan").hide();
		        $("#tr_sks_beban_penugasan").hide();
		        $("#tr_bukti_kinerja").hide();
		        $("#tr_sks_bukti_kinerja").hide();
		        $("#tr_capaian").hide();
			}
		</script>
	<?php }elseif($kode == 'D'){?>
		<script type="text/javascript">
			window.onload=function(){
		        document.getElementById("label_jumlah").innerText = "Jumlah MHS";
		         $("#tr_beban_penugasan").hide();
		        $("#tr_sks_beban_penugasan").hide();
		        $("#tr_bukti_kinerja").hide();
		        $("#tr_sks_bukti_kinerja").hide();
		        $("#tr_capaian").hide();
			}
		</script>
	<?php }
?>
<!-- TF -->

<!-- created by DNG A BMTR -->
<script type="text/javascript">
    function auto_fill_sks(){
        $.ajax({
            url     : '<?php echo base_url(); ?>/bkd/dosen/remun/auto_fill_sks',
            type    : 'POST',
            data    : $("#form-hybrid").serialize(),

            success : function(data){
                $("#sks1").val(data);
                $("#sks2").val(data);
                autosum();
;            }
        });
    }
</script>
<!-- created by DNG A BMTR -->
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
<?php echo $this->s00_lib_output->output_info_dsn();?>
<?php 
	$q = 'kep';
		#$data = $this->mdl_tnde->get_api('tnde_pegawai/get_pejabat', 'json', 'POST', array('api_kode' => 4004, 'api_subkode' => 3,'api_search' => array($q)));
		# cek kode
		switch($kode){
			case "A" : $title = 'pendidikan'; break;
			case "B" : $title = 'penelitian'; break;
			case "C" : $title = 'pengabdian'; break;
			case "D" : $title = 'penunjang'; break;
			case "F" : $title = 'Narasumber/Pembicara'; break;
			case "G" : $title = 'Publikasi'; break;
			case "H" : $title = 'Hak Kekayaan Intelektual'; break;
		}
	?>
	<?php
		# set value 
		if (empty($current_data)){
			$kd_bk = '';
			$jns_keg = '';
			$bkt_penugasan = '';
			$sks_penugasan = 0;
			$ms_penugasan = ''; $lama = ''; $masa = '';
			$bkt_dokumen = '';
			$sks_bkt = 0;
			$recomendasi = '';
			$caption = '- Rekomendasi -';
			$jml_jam = 0;
			$capaian = '';
			$file_penugasan = ''; $fp = '';
			$file_capaian = ''; $fc = '';
			$outcome = '';
			
			# data detail pendidikan
			$kd_kat = ''; $nm_kat = '- Kategori Publikasi -';
			$nm_kat = '- Pilih Kategori -';
			$nm_kegiatan = $jns_keg;
			$jenjang = '- Pilih Jenjang -';
			$tempat = '';
			$jml_mhs = 0;

			//$jml_sks = $value->JML_SKS;
			$jml_tm ='';
			$jenis_kelas = '- Pilih Kelas -';
			$pertemuan_pm = '';
			$jml_sks ='';
			$nm_prodi='';
			$satuan='';
			//$jml_dosen = '';
			# === data detail penelitian
			$bt_mulai = '';
			$bt_selesai = '';
			$judul = '';
			$dosen_partner = '';
			$sumber_dana = '';
			$jumlah_dana = 0;
			$status_peneliti = '- Pilih status -';
			$laman_publikasi = '';
			
			# === data detail narasumber
			$lokasi_acara = '';
			$kd_tingkat = '';
			$nm_tingkat = '- Pilih Tingkat -';

			/*if($_POST['kd_kat']==''){
				$action = 'tambah/A';
			}else{
				$action = 'simpan_data_remun';
			}*/
			$action = 'simpan_data_remun';
			$btn = 'Simpan';
			
			if($kode=='G'){
				$kd_dp = '';
				$judul = '';
				$pada = '';
				$tingkat = ''; $tlabel = '- Pilih tingkat -';
				$tanggal_pub = '';
				$penerbit = '';
				$akreditasi = ''; $takre = '- Pilih akreditasi -';
				$label = 'Simpan';
				$action = 'simpan_data_publikasi';
			}
			
			if($kode=='H'){
				$kd_haki = '';
				$judul = '';
				$no_sk = '';
				$tingkat = ''; $tlabel = '- Pilih tingkat -';
				$tanggal_pub = '';
				$penerbit = '';
				$akreditasi = ''; $takre = '- Pilih akreditasi -';
				$btn = 'Simpan';
				$action = 'simpan_data_haki';
			}
		}else{
			//print_r($current_data);
			foreach ($current_data as $value);
				$kd_bk = $value->KD_BK;
				$jns_keg = $value->JENIS_KEGIATAN;
				$bkt_penugasan = $value->BKT_PENUGASAN;
				$sks_penugasan = (float) str_replace(",", ".", $value->SKS_PENUGASAN);
				$ms_penugasan = $value->MASA_PENUGASAN; $a = explode(' ',$ms_penugasan); $lama = $a[0]; $masa = $a[1];
				$bkt_dokumen = $value->BKT_DOKUMEN;
				$sks_bkt = (float) str_replace(",", ".", $value->SKS_BKT);
				$recomendasi = $value->REKOMENDASI;
				$caption = $recomendasi;
				$jml_jam = $value->JML_JAM;
				$capaian = $value->CAPAIAN;
				$file_penugasan = $value->FILE_PENUGASAN;
				$file_capaian = $value->FILE_CAPAIAN;
				$outcome = $value->OUTCOME;
				if($file_penugasan == 0 || $file_penugasan == '') $fp = 'Tidak ada file penugasan'; 
					else $fp = '<a href='.base_url().'uploads/DataBkd/FilesBebanKerja/'.$file_penugasan.'>Lihat File</a>';
				if($file_capaian == 0 || $file_capaian == '') $fc = 'Tidak ada file capaian'; 
					else $fc = '<a href='.base_url().'uploads/DataBkd/FilesBebanKerja/'.$file_capaian.'>Lihat File</a>';
					
			$action = 'update_remun';
			$btn = 'Update';
				#data pendidikan
				if($kode == 'A'){
					$kd_kat = $value->KD_KAT;
					$nm_kat = $value->NM_KAT;
					$nm_kegiatan = $jns_keg;
					$jenjang = $value->JENJANG;
					$tempat = $value->TEMPAT;
					$jml_mhs = $value->JML_MHS;

					$jml_sks = $value->JML_SKS;
					$jml_tm = $value->JML_TM;
					$jenis_kelas = $value->KELAS;
					$pertemuan_pm = $value->JML_PERTEMUAN_PM;
					$jml_dosen = $value->JML_DOSEN;
					$nm_prodi=$value->NM_PRODI;
					$satuan = $value->SATUAN;
				}elseif($kode == 'D'){
					$kd_kat = $value->KD_KAT;
					$nm_kat = $value->NM_KAT;
					$nm_kegiatan = $jns_keg;
					$jenjang = $value->JENJANG;
					$tempat = $value->TEMPAT;
					$jml_mhs = $value->JML_MHS;

					$jml_sks = $value->JML_SKS;
					$jml_tm = $value->JML_TM;
					$jenis_kelas = $value->KELAS;
					$pertemuan_pm = $value->JML_PERTEMUAN_PM;
					$jml_dosen = $value->JML_DOSEN;
					$nm_prodi=$value->NM_PRODI;
					$satuan = $value->SATUAN;
				}
				# data detail pendidikan
				else if($kode == 'B'){
					$kd_kat = $value->KD_KAT;
					$nm_kat = $value->NM_KAT;
					$nm_kegiatan = $jns_keg;
					$jenjang = $value->JENJANG;
					$tempat = $value->TEMPAT;
					$jml_mhs = $value->JML_MHS;

					$jml_sks = $value->JML_SKS;
					$jml_tm = $value->JML_TM;
					$jenis_kelas = $value->KELAS;
					$pertemuan_pm = $value->JML_PERTEMUAN_PM;
					$jml_dosen = $value->JML_DOSEN;
					$nm_prodi=$value->NM_PRODI;
					$satuan = $value->SATUAN;
				}else if ($kode == 'C'){
					$kd_kat = $value->KD_KAT;
					$nm_kat = $value->NM_KAT;
					$bt_mulai = date('d/m/Y',strtotime($value->BT_MULAI));
					$bt_selesai = date('d/m/Y',strtotime($value->BT_SELESAI));
					$judul = $value->JUDUL_PENGABDIAN;
					$sumber_dana = $value->SUMBER_DANA;
					$jumlah_dana = $value->JUMLAH_DANA;
				}else if ($kode == 'G'){
					$val=$value;
					$kd_dp = $val->KD_DP;
					$judul = $val->JUDUL_PUBLIKASI;
					$pada = $val->PADA;
					$tingkat = $val->TINGKAT; $tlabel = $tingkat;
					$tanggal_pub = date('d/m/Y', strtotime($val->TANGGAL_PUB));
					$penerbit = $val->PENERBIT;
					$akreditasi = $val->AKREDITASI; $takre = $akreditasi;
					$label = 'Update';
					$action = 'update_data_publikasi';
				}else if ($kode == 'H'){
					$val=$value;
					$kd_haki = $val->KD_HAKI;
					$judul = $val->JUDUL_HAKI;
					$no_sk = $val->NOMOR_SK;
					$tingkat = $val->TINGKAT; $tlabel = $tingkat;
					$tanggal_pub = date('d/m/Y', strtotime($val->TANGGAL_SK));
					$penerbit = $val->PENERBIT_SK;
					$akreditasi = $val->PEMILIK_HAK; $takre = $akreditasi;
					$label = 'Update';
					$action = 'update_data_haki';
				}else if($kode == 'F'){
					$kd_tingkat = $value->KD_TINGKAT;
					$nm_tingkat = $value->NM_TINGKAT;
					$judul = $value->JUDUL_ACARA;
					$bt_mulai = date('d/m/Y',strtotime($value->BT_MULAI));
					$bt_selesai = date('d/m/Y',strtotime($value->BT_SELESAI));
					$lokasi_acara = $value->LOKASI_ACARA;
					$laman_publikasi = $value->LAMAN_PUBLIKASI;
					$status_peneliti = $value->STATUS_PENELITI;
				}
				
				
				#echo $file_capaian.'#'.$file_penugasan;
			#print_r($current_data);
		} 
	?>
	<?php
		if(isset($current_data)){
			echo '<script type="text/javascript">
			window.onload=function(){
		        $("#tr_beban_penugasan").hide();
		        $("#tr_sks_beban_penugasan").hide();
		        $("#tr_bukti_kinerja").hide();
		        $("#tr_sks_bukti_kinerja").hide();
		        $("#tr_capaian").hide();
			}
		</script>';
		}
	?>
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/remun/pendidikan';?>">BKD Remun</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/remun/data/'.$kode;?>"><?php echo ucwords($title);?></a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/remun/tambah/A';?>">Tambah Data</a></li>
</ul>
<br/>
		
<div id="content">

<h2>Form BKD Remun Bidang <?php echo ucwords($title);?></h2>
<h3>Tahun Akademik : <?php echo $ta;?>, Semester : <?php echo $smt;?></h3>
<?php
	$a = $this->session->flashdata('msg');
    if($a!=null){?>
        <div class="alert alert-<?php echo $a[0]?> alert-msg">
            <?php echo $a[1]?>
        </div>
    <?php }
    //$input_terakhir = $this->session->userdata('msg_back');
    
?>
<form method= "POST" action="<?php echo base_url().'bkd/dosen/remun/'.$action;?>" enctype="multipart/form-data" id="form-hybrid" onchange="auto_fill_sks();">
	<input type="hidden" name="kd_bk" id="kd_bk" value="<?php echo $kd_bk;?>">
	<input type="hidden" name="kd_jbk" value="<?php echo $kode;?>">
	<?php if ($kode == 'A'){ ?>
			<table class="table table-responsive table-bordered table-hover" >
				<tr><th colspan="4">Data Pendidikan / Pengajaran</td></tr>
				<tr>
					<td>Kategori</td>
					<!-- <td colspan="3"><textarea name="pend_kategori" id="autocomplete" class="autocompleteA input-xxlarge" placeholder="Tulis kategori kegiatan sesuai dengan pilihan yang tersedia" required <?php if(ISSET($current_data)){?>
						readonly="readonly"
					<?php }?>><?php if(ISSET($current_data)){echo $nm_kat;}?></textarea></td> -->
					<td colspan="3">
						<select name="pend_kategori" id="kategori" class="input-xxlarge" <?php if(ISSET($current_data)){?>
						readonly="readonly"
					<?php }?>>
							<?php
								if(ISSET($current_data)){
									foreach ($list_kategori as $key) {?>
												<?php
													if($key['KD_KAT'] == $kd_kat){?>
														<option value="<?php echo $nm_kat;?>" selected><?php echo $nm_kat;?></option>
													<?php }else{?>
														<option value="<?php echo $key['KD_KAT'];?>" disabled="disabled"><?php echo $key['NM_KAT'];?></option>
													<?php }
												?>
										<?php }	
								}else{?>
										<option>- Pilih Kategori -</option>
										<?php
											foreach ($list_kategori as $key) {?>
												<option value="<?php echo $key['KD_KAT'];?>"><?php echo $key['NM_KAT'];?></option>
											<?php }
										?>
								<?php }
							?>
						</select>
					</td>
				</tr>
				<input type="hidden" name="kd_kat" id="kd_kat" value="<?php if(ISSET($current_data)){echo $kd_kat;}?>">
				<input type="hidden" name="satuan" id="satuan" value="<?php if(ISSET($current_data)){echo $satuan;}?>">
				<!-- <input type="text" name="nilai_kat" id="nilai_kat" value="<?php if(ISSET($current_data)){echo $satuan;}?>"> -->
				<tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3">
						<textarea name="jenis_kegiatan" class="input-xxlarge" required><?php if(ISSET($current_data)){
							echo $jns_keg;
						}else{
							echo $jns_keg;
						}?></textarea>
					</td>
				</tr>
				<tr>
					<?php $data_jenjang = array("D3", "S1", "S2", "S3");?>
					<td>Jenjang</td>
					<td>
						<?php if(ISSET($current_data)){?>
						<select name="jenjang" class="input-medium" id="jenjang" required>
								<?php foreach ($data_jenjang as $key) {
									if($key == $jenjang){?>
										<option value="<?php echo $jenjang;?>" selected><?php echo $jenjang;?></option>
									<?php }else{?>
										<option value="<?php echo $key;?>"><?php echo $key;?></option>
									<?php }
								}?>
						</select>
						<?php }else{?>
						<select name="jenjang" class="input-medium" id="jenjang" required>
							<option value=""><?php echo $jenjang;?></option>
							<?php foreach ($data_jenjang as $key) {?>
								<option value="<?php echo $key;?>"><?php echo $key;?></option>
							<?php }?>
						</select>							
							<?php }?>
					</td>
					<td><?php if(isset($current_data)){
						if($satuan == 'SKS'){
							$satuan = 'MHS';
						}else{
							$satuan = $satuan;
						}echo "Jumlah ".$satuan;}else{?><label id="label_jumlah" name="label_jumlah"></label><?php }?>
					</td>
						
					<!-- <td id="label_jml_bln">Jumlah Bulan</td> -->
					<td><input type="number" name="jml_mhs" id="jml_mhs" class="input-small" min="0"
						value="<?php if(isset($current_data)){
								echo $jml_mhs;
								}?>" required></td>
				</tr>
				
				<tr id="row_sks_kelas">
					
					<td>Kelas</td>
                    <?php
                        $kelas= array("A" => "REGULER", "B"=>"NON REGULER", "C"=>"INTERNASIONAL");
                    ?>
                    <?php
                    	if(ISSET($current_data)){
                    		if($nilai_kat[$kd_kat]['NILAI_KAT'] == "JUMLAH"){?>
                    			<td id="td_kelas" colspan="3"><select name="jenis_kelas" class="input-medium" id="jenis_kelas" required>
			                        <!-- <option value="<?php echo $jenis_kelas?>"><?php echo $jenis_kelas?></option> -->
			                        <option value=""><?php echo "-Pilih Kelas-"?></option>
			                        <?php
			                            foreach ($kelas as $key => $value) {?>
			                                <?php
			                                    if($jenis_kelas == $key){?>
			                                        <option value="<?php echo $jenis_kelas?>" selected><?php echo $value?></option>
			                                    <?php }else{?>
			                                        <option value="<?php echo $key?>"><?php echo $value?></option>
			                                    <?php }
			                                ?>
			                                
			                            <?php }
			                        ?>
			                    </select></td>
                    		<?php }elseif($nilai_kat[$kd_kat]['NILAI_KAT'] == "SKS"){?>
                    			<td id="td_kelas"><select name="jenis_kelas" class="input-medium" id="jenis_kelas" required>
                        <!-- <option value="<?php echo $jenis_kelas?>"><?php echo $jenis_kelas?></option> -->
                        <option value=""><?php echo "- Pilih Kelas -"?></option>
                        <?php
                            foreach ($kelas as $key => $value) {?>
                                <?php
                                    if($jenis_kelas == $key){?>
                                        <option value="<?php echo $jenis_kelas?>" selected><?php echo $value?></option>
                                    <?php }else{?>
                                        <option value="<?php echo $key?>"><?php echo $value?></option>
                                    <?php }
                                ?>
                                
                            <?php }
                        ?>
                    </select></td>
                    		<?php }
                    	}else{?>
                    		<td id="td_kelas">
                    			<select name="jenis_kelas" class="input-medium" id="jenis_kelas" required>
			                        <option value=""><?php echo "- Pilih Kelas -"?></option>
			                        <?php
			                            foreach ($kelas as $key => $value) {?>
			                                <?php
			                                    if($jenis_kelas == $key){?>
			                                        <option value="<?php echo $jenis_kelas?>" selected><?php echo $value?></option>
			                                    <?php }else{?>
			                                        <option value="<?php echo $key?>"><?php echo $value?></option>
			                                    <?php }
			                                ?>
			                                
			                            <?php }
			                        ?>
		                    </select>
		                	</td>
                    	<?php }
                    ?>
                    
                    <?php
                   		if(ISSET($current_data)){
                            if($nilai_kat[$kd_kat]['NILAI_KAT'] == "JUMLAH"){?>
                                    <input type="hidden" class="input-small" name="jml_sks" min="0" id="jml_sks" value="<?php echo $jml_sks;?>">
                          <?php  }elseif($nilai_kat[$kd_kat]['NILAI_KAT'] == "SKS"){?>
                            	<td id="label_jumlah_sks">Jumlah SKS</td>
								<td id="input_jumlah_sks"><input type="number" class="input-small" name="jml_sks" min="0" id="jml_sks" value="<?php echo $jml_sks;?>" required></td>
                            <?php }
                        }else{?>
                        	<td id="label_jumlah_sks">Jumlah SKS</td>
							<td id="input_jumlah_sks"><input type="number" class="input-small" name="jml_sks" min="0" id="jml_sks" value="<?php echo $jml_sks;?>" required></td>
                       <?php }?>
				</tr>
				<?php
					if(ISSET($current_data)){
						if($nilai_kat[$kd_kat]['NILAI_KAT']=="JUMLAH"){?>
							<!-- tidak ditampilkan edit jumlah pertemuan dan pertemuan perminggu -->
						<?php }elseif($nilai_kat[$kd_kat]['NILAI_KAT']=="SKS"){?>
								<tr id="row_pertemuan">
			                    <td>Jumlah Tatap Muka</td>
			                    <td><input type="number" min="0" class="input-small" name="jml_tatap_muka" id="jml_tatap_muka" value="<?php echo $jml_tm?>"></td>
								<td>Pertemuan Per Minggu</td>
								<td><input type="number" min="0" name="pertemuan_pm" class="input-medium" required id="pertemuan_pm" value="<?php echo $pertemuan_pm;?>"></td>
							</tr>
						<?php }	
					}else{?>
						<tr id="row_pertemuan">
		                    <td>Jumlah Tatap Muka</td>
		                    <td><input type="number" min="0" class="input-small" name="jml_tatap_muka" id="jml_tatap_muka" value="<?php echo $jml_tm?>"></td>
							<td>Pertemuan Per Minggu</td>
							<td><input type="number" min="0" name="pertemuan_pm" class="input-medium" required id="pertemuan_pm" value="<?php echo $pertemuan_pm;?>"></td>
						</tr>
					<?php }
				?>
				
				<tr>
					<td>Program Studi</td>
					<!-- <td><input type="text" name="nama_prodi" id="prodi" class="prodi input-xlarge" value="<?php echo $nm_prodi;?>" required></td> -->
					<td>
					<select id="prodi" class="input-xlarge" name="nama_prodi" required>
	                    <?php
	                    	if(isset($current_data)){?>
	                    		<option value="<?php echo $nm_prodi?>" selected><?php echo $nm_prodi;?></option>
	                    	<?php }else{?>
								<option value="">- Pilih Program Studi -</option>
	                    	<?php }
	                    ?>
	                <!-- Dropdown List Option -->
	                </select></td>
					<td>Jumlah Dosen</td>
					<td colspan="3"><input type="number" name="jml_dosen" value="1" readonly="" class="input-small"></td>
				</tr>
				<!-- <tr>
					<td><input type="hidden" name="nama_prodi" id="nama_prodi"></td>
				</tr> -->
				<tr>
					<td>Semester</td>
					<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('smt');?>"><?php echo $this->session->userdata('smt');?></option></select></td>
					<td>Tahun <br/>Akademik</td>
					<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option></select></td>
				</tr>
				<tr>
					<td>Tempat</td>
					<td colspan="3">
						<textarea name="tempat" id="tempat" class="input-xxlarge" required><?php echo $tempat;?></textarea>
					</td>
				</tr>
				<tr><th colspan="4">Data Beban Kerja</td></tr>
				<tr>
					<td>Masa Penugasan</td>
					<td><input type="number" min="0" name="lama" id="lama" class="input-small" value="<?php echo $lama; ?>" required></td>
					<td colspan="2">
						<?php
							$data_masa = array("Tahun", "Semester", "Bulan", "Hari");
						?>	
							<?php
								if(ISSET($current_data)){?>
									<select name="masa" id="masa" required>
									<?php foreach ($data_masa as $key) {
										if($key == $masa){?>
											<option value="<?php echo $masa;?>" selected><?php echo $masa;?></option>
										<?php }else{?>
											<option value="<?php echo $key;?>"><?php echo $key;?></option>
										<?php }
									}?>
									</select>
								<?php }else{?>
								<select name="masa" id="masa" required>
									<option value="<?php echo $masa;?>" selected><?php echo $masa;?></option>
									<?php foreach ($data_masa as $key) {?>
											<option value="<?php echo $key;?>"><?php echo $key;?></option>
										<?php 
									}?>
								</select>
								<?php }	
							?>
					</td>
				</tr>
				<tr id="tr_beban_penugasan">
					<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
					<td>
						<input type="hidden" name="bukti_penugasan" id="bukti">
						<p class="btn-group">
							<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label">
						<?php 
							echo $bkt_penugasan;
						?></div>
					</td>
				</tr>
				<tr id="tr_sks_beban_penugasan">
					<td></td>
					<td>SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small"
						value="<?php echo $sks_penugasan;?>" onBlur="return autosum()"
                        ></td>
					<td></td>
				</tr>
				<tr id="tr_bukti_kinerja">
					<td><b>Kinerja</b><br/>Bukti Dokumen</td>
					<td>
						<input type="hidden" name="bukti_dokumen" id="bukti2">
						<p class="btn-group">
							<span class="btn btn-isi2" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari2" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr id="tr_sks_bukti_kinerja">
					<td></td>
					<td>SKS : <input type="number" name="sks2" id="sks2" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_bkt;?>" onBlur="return autosum()"
                        ></td>
					<td></td>
				</tr>
				<tr id="tr_capaian">
					<td>Capaian (%)</td>
					<td colspan="3"><input type="text" name="capaian" id="capaian" class="bogol input-small" value="<?php echo $capaian;?>" readonly></td>
				</tr>
				<tr id="tr_rekomendasi">
					<td>Rekomendasi</td>
					<td colspan="3">
						<?php
							$data_rekomendasi = array("SELESAI"=>"A. SELESAI", "LANJUTKAN"=>"B. LANJUTKAN", "BEBAN LEBIH"=>"C. BEBAN LEBIH", "LAINNYA"=>"D. LAINNYA");
						?>
						<?php
						if(ISSET($current_data)){?>
								<select name="rekomendasi" id="idrekomendasi">
									<?php
										foreach ($data_rekomendasi as $key => $value) {
											if($key == $caption){?>
												<option value="<?php echo $caption;?>" selected><?php echo $value;?></option>
											<?php }else{?>
												<option value="<?php echo $key;?>"><?php echo $value;?></option>
											<?php }
										}
									?>
								</select>
							<?php }else{?>
								<select name="rekomendasi" id="idrekomendasi">
									<option value="<?php echo $recomendasi;?>" selected><?php echo $caption;?></option>
									<?php
										foreach ($data_rekomendasi as $key => $value) {?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php }
									?>
								</select>
							<?php }
						?>
					</td>
				</tr>
			</table>
		<?php } ?>
		<?php if ($kode == 'B'){ ?>
		<?php	/* <table class="table table-condensed">
				<tr><th colspan="4">Data Penelitian</th></tr>
				<tr>
					<td width="20%">Kategori</td>
					<td colspan="3">
						<select name="kd_kat" class="input-xxlarge" required>
						<option value="<?php echo $kd_kat;?>"><?php echo $nm_kat;?></option>
						<?php foreach ($kategori as $kat){?>
							<option value="<?php echo $kat->KD_KAT;?>"><?php echo $kat->NM_KAT;?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Judul Penelitian</td>
					<td colspan="3"><textarea name="judul_penelitian" class="input-xxlarge" id="judul" onblur="return auto_fill('Penelitian')" required><?php echo $judul;?></textarea></td>
				</tr>
				<tr>
					<td>Tanggal Mulai</td>
					<td width="40%"><input type="text" class="datepicker input-medium" name="bt_mulai" value="<?php echo $bt_mulai;?>" readonly></td>
					<td width="15%">Tanggal Selesai</td>
					<td><input type="text" class="datepicker input-medium" name="bt_selesai" value="<?php echo $bt_selesai;?>" readonly></td>
				</tr>
				<tr>
					<td>Status Peneliti</td>
					<td><select name="status_peneliti" class="input-medium" required>
						<option value="<?php echo $status_peneliti;?>"><?php echo $status_peneliti;?></option>
						<option value="KETUA">KETUA</option>
						<option value="WAKIL KETUA">WAKIL KETUA</option>
						<option value="ANGGOTA">ANGGOTA</option>
					</select></td>
					<td colspan="2" class="link" id="partner-penelitian-btn" style="cursor:pointer"><i class="icon-user"></i> Partner Penelitian</td>
				</tr>

				<tr id="partner-penelitian" style="display:none">
					<td>Partner Penelitian</td>
					<td colspan="3">
						<div class="tujuan-surat">
							<div class="auto-surat grup">
								<div class="label-input">Dosen/Staff</div>
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
										<td><input type="text" name="lain[]" class="input-grup input-xlarge"/></td>
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
					<td>Sumber Dana</td>
					<td colspan="3"><input type="text" name="sumber_dana" class="input-xxlarge" value="<?php echo $sumber_dana;?>"></td>
				</tr>
				<tr>
					<td>Jumlah Dana</td>
					<td colspan="3"><input type="number" name="jumlah_dana" class="input-medium" value="<?php echo $jumlah_dana;?>"></td>
				</tr>
				<tr>
					<td>Laman Publikasi</td>
					<td colspan="3"><input type="url" name="laman_publikasi" class="input-xxlarge" value="<?php echo $laman_publikasi;?>" placeholder="Masukkan URL"></td>
				</tr> */?>
				<?php /*	
				$value="";
				if (isset($current_data) and !empty($current_data)){
					foreach($current_data as $value){}
				}
				$this->load->view('bkd/dosen/form/penelitian_remun',array('value'=>$value));*/
				/*$cur_data=array();*/
				/*if(isset($current_data))$cur_data=array('current_data'=>$current_data);*/
				/*$this->load->view('bkd/dosen/bkd_form',$cur_data);*/ ?>
	<table class="table table-responsive table-bordered table-hover">
	<tr><th colspan="4">Data Penelitian</th></tr>
	<tr>
		<td>Kategori</td>
		<!-- <td colspan="3"><textarea name="jenis_kegiatan" id="autocomplete" class="autocompleteB input-xxlarge" placeholder="Tulis jenis kegiatan sesuai pilihan yang ada" onblur="return auto_fill('Penelitian')" required><?php if(isset($value->JUDUL_PENELITIAN)) echo $value->JUDUL_PENELITIAN;?></textarea></td> -->

		<td colspan="3">
			<select name="pend_kategori" id="kategori" class="input-xxlarge" <?php if(ISSET($current_data)){?>
			readonly="readonly"
		<?php }?>>
				<?php
					if(ISSET($current_data)){
						foreach ($list_kategori as $key) {?>
									<?php
										if($key['KD_KAT'] == $kd_kat){?>
											<option value="<?php echo $nm_kat;?>" selected><?php echo $nm_kat;?></option>
										<?php }else{?>
											<option value="<?php echo $key['KD_KAT'];?>" disabled="disabled"><?php echo $key['NM_KAT'];?></option>
										<?php }
									?>
							<?php }	
					}else{?>
							<option>- Pilih Kategori -</option>
							<?php
								foreach ($list_kategori as $key) {?>
									<option value="<?php echo $key['KD_KAT'];?>"><?php echo $key['NM_KAT'];?></option>
								<?php }
							?>
					<?php }
				?>
			</select>
		</td>
	</tr>
	<input type="hidden" name="kd_kat" id="kd_kat" value="<?php if(ISSET($current_data)){echo $kd_kat;}?>">
	<input type="hidden" name="satuan" id="satuan" value="<?php if(ISSET($current_data)){echo $satuan;}?>">
	<!-- <tr>
		<td>Nama / jenis Kegiatan</td>
		<td colspan="3"><textarea name="jenis_kegiatan" class="input-xxlarge" id="judul" onblur="return auto_fill('Penelitian')" required><?php if(isset($value->JUDUL_PENELITIAN)) echo $value->JUDUL_PENELITIAN;?></textarea></td>
	</tr> -->
	<td>Nama/Jenis Kegiatan</td>
	<td colspan="3">
		<textarea name="jenis_kegiatan" class="input-xxlarge" required><?php if(ISSET($current_data)){
			echo $jns_keg;
		}else{
			echo $jns_keg;
		}?></textarea>
	</td>
	<!-- <tr>
		<td>Tanggal Mulai</td>
		<td width="40%"><input type="text" class="datepicker input-medium" name="bt_mulai" value="<?php if(isset($value->BT_MULAI)) echo $value->BT_MULAI; ?>" readonly></td>
		<td width="15%">Tanggal Selesai</td>
		<td><input type="text" class="datepicker input-medium" name="bt_selesai" value="<?php if(isset($value->BT_SELESAI)) echo $value->BT_SELESAI;?>" readonly></td>
	</tr> -->
	<tr>
		<td>Tempat</td>
		<td><textarea name="tempat" id="tempat" class="input-xlarge" required><?php echo $tempat;?></textarea></td>
		<td><?php if(isset($current_data)){
			if($satuan == 'SKS'){
				$satuan = 'MHS';
			}else{
				$satuan = $satuan;
			}echo "Jumlah ".$satuan;}else{?><label id="label_jumlah" name="label_jumlah"></label><?php }?>
		</td>
			
		<!-- <td id="label_jml_bln">Jumlah Bulan</td> -->
		<td><input type="number" name="jml_mhs" id="jml_mhs" class="input-small" min="0"
			value="<?php if(isset($current_data)){
					echo $jml_mhs;
					}?>" required>
		</td>
	</tr>
	<tr>
		<td>Semester</td>
		<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('smt');?>"><?php echo $this->session->userdata('smt');?></option></select></td>
		<td>Tahun <br/>Akademik</td>
		<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option></select></td>
	</tr>
	<!-- <input type="hidden" name="kd_bk" value="<?php echo $kd_bk?>"/> -->
	<tr><th colspan="4">Data Beban Kerja</th></tr>
				<!-- <tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea name="jenis_kegiatan" id="kegiatan" class="input-xxlarge" required><?php if(isset($jns_keg)) echo $jns_keg;?></textarea></td>
				</tr> -->
				
				<tr>
					<td>Masa Penugasan</td>
					<td><input type="number" min="0" name="lama" id="lama" class="input-small" value="<?php echo $lama;?>" required></td>
					<td colspan="2">

						<?php
							$data_masa = array("Tahun", "Semester", "Bulan", "Hari");
						?>	
							<?php
								if(ISSET($current_data)){?>
									<select name="masa" id="masa" required>
									<?php foreach ($data_masa as $key) {
										if($key == $masa){?>
											<option value="<?php echo $masa;?>" selected><?php echo $masa;?></option>
										<?php }else{?>
											<option value="<?php echo $key;?>"><?php echo $key;?></option>
										<?php }
									}?>
									</select>
								<?php }else{?>
								<select name="masa" id="masa" required>
									<option value="<?php echo $masa;?>" selected><?php echo $masa;?></option>
									<?php foreach ($data_masa as $key) {?>
											<option value="<?php echo $key;?>"><?php echo $key;?></option>
										<?php 
									}?>
								</select>
								<?php }	
							?>
					</td>
				</tr>
				<tr id="tr_beban_penugasan">
					<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
					<td>
						<input type="hidden" name="bukti_penugasan" id="bukti">
						<p class="btn-group">
							<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label">
						<?php 
							echo $bkt_penugasan;
						?></div>
					</td>
				</tr>
				<tr id="tr_sks_beban_penugasan">
					<td></td>
					<td colspan="4">SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_penugasan;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr id="tr_bukti_kinerja">
					<td><b>Kinerja</b><br/>Bukti Dokumen</td>
					<td>
						<input type="hidden" name="bukti_dokumen" id="bukti2">
						<p class="btn-group">
							<span class="btn btn-isi2" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari2" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr id="tr_sks_bukti_kinerja">
					<td></td>
					<td colspan="4">SKS : <input type="number" name="sks2" id="sks2" class="input-small" step="any" min="0" value="<?php echo $sks_bkt;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr id="tr_capaian">
					<td>Capaian (%)</td>
					<td colspan="3"><input type="text" name="capaian" id="capaian" class="input-small" value="<?php echo $capaian;?>" readonly></td>
				</tr>
				<tr id="tr_rekomendasi">
					<td>Rekomendasi</td>
					<td colspan="3">
						<?php
							$data_rekomendasi = array("SELESAI"=>"A. SELESAI", "LANJUTKAN"=>"B. LANJUTKAN", "BEBAN LEBIH"=>"C. BEBAN LEBIH", "LAINNYA"=>"D. LAINNYA");
						?>
						<?php
						if(ISSET($current_data)){?>
								<select name="rekomendasi" id="idrekomendasi">
									<?php
										foreach ($data_rekomendasi as $key => $value) {
											if($key == $caption){?>
												<option value="<?php echo $caption;?>" selected><?php echo $value;?></option>
											<?php }else{?>
												<option value="<?php echo $key;?>"><?php echo $value;?></option>
											<?php }
										}
									?>
								</select>
							<?php }else{?>
								<select name="rekomendasi" id="idrekomendasi">
									<option value="<?php echo $recomendasi;?>" selected><?php echo $caption;?></option>
									<?php
										foreach ($data_rekomendasi as $key => $value) {?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php }
									?>
								</select>
							<?php }
						?>
					</td>
				</tr>
			</table>
		<?php } ?>
		<!-- end form SIPKD PENELITIAN -->
		<!-- ================================================================================================================================================= -->
		<!-- form SIPKD PUBLIKASI -->
		<?php if ($kode == 'G'){ ?>
			<?php echo validation_errors(); ?>
			<input type="hidden" name="kd_dp" value="<?php echo $kd_dp;?>">
			<table class="table table-condensd">
				<tr>
					<td>Judul</td>
					<td colspan="3"><textarea id="judul" name="judul" class="input-xxlarge" onblur="return auto_fill('Publikasi')" required><?php echo $judul;?></textarea>
					<hr><div id="partner-penelitian-btn" class="link" style="cursor:pointer"><i class="icon-user"></i> Partner Publikasi</div>
					</td>
				</tr>
				<tr id="partner-penelitian" style="display:none">
					<td>Partner Publikasi</td>
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
					<td>Dipublikasikan pada</td>
					<td colspan="3"><textarea name="pada" class="input-xxlarge"><?php echo $pada;?></textarea></td>
				</tr>
				<tr>
					<td>Tingkat</td>
					<td><select name="tingkat">
							<option value="<?php echo $tingkat;?>"><?php echo $tlabel;?></option>
							<option value="LOKAL">LOKAL</option>
							<option value="NASIONAL">NASIONAL</option>
							<option value="INTERNASIONAL">INTERNASIONAL</option>
						</select>
					</td>
					<td>Tanggal Penyajian</td>
					<td><input type="text" name="tanggal_pub" class="datepicker input-medium" value="<?php echo $tanggal_pub;?>"></td>
				</tr>
				<tr>
					<td>Penerbit</td>
					<td colspan="3"><input type="text" name="penerbit" class="input-xxlarge" value="<?php echo $penerbit;?>"></td>
				</tr>
				<tr>
					<td>Akreditasi</td>
					<td colspan="3"><select name="akreditasi">
							<option value="<?php echo $akreditasi;?>"><?php echo $takre;?></option>
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="LAIN">LAINNYA</option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td><input type="submit" name="submit" value="<?php echo $label;?>" class="btn btn-uin btn-inverse btn-small">
						<input type="reset" name="reset" value="Batal" class="btn btn-uin btn-small black"></td>
				</tr>			
				<?php 	
				$cur_data=array();
				if(isset($current_data))$cur_data=array('current_data'=>$current_data);
				$this->load->view('bkd/dosen/bkd_form',$cur_data);
				?>
			</table>
	
		<?php } ?>
		<!-- end form SIPKD PUBLIKASI -->
				<!-- ================================================================================================================================================= -->
		<!-- form SIPKD HAKI -->
		<?php if ($kode == 'H'){ ?>
			<input type="hidden" name="kd_haki" value="<?php if(isset($kd_haki))echo $kd_haki;?>">
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
						<?php if(isset($tingkat)):?>
							<option value="<?php if(isset($tingkat)) echo $tingkat;?>"><?php if(isset($tlabel)) echo $tlabel;?></option>
						<?php endif ?>		
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
			</table>
	
		<?php } ?>
		<!-- end form SIPKD PENELITIAN -->
		
		<!-- form SIPKD PENGABDIAN MASYARAKAT -->
			<?php if ($kode == 'C'){ ?>
			<table class="table table-condensed">
				<tr><th colspan="4">Data Pengabdian Masyarakat</th></tr>
				<tr>
					<td width="20%">Kategori</td>
					<td colspan="3">
						<select name="kd_kat" class="input-xxlarge" required>
						<option value="<?php echo $kd_kat;?>"><?php echo $nm_kat;?></option>
						<?php foreach ($kategori as $kat){?>
							<option value="<?php echo $kat->KD_KAT;?>"><?php echo $kat->NM_KAT;?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Judul Pengabdian</td>
					<td colspan="3"><textarea name="judul_penelitian" id="judul" class="input-xxlarge" onblur = "return auto_fill('Pengabdian')" required><?php echo $judul;?></textarea></td>
				</tr>
				<tr>
					<td width="20%">Tanggal Mulai</td>
					<td width="40%"><input type="text" class="datepicker input-medium" name="bt_mulai" value="<?php echo $bt_mulai;?>" readonly></td>
					<td width="15%">Tanggal Selesai</td>
					<td><input type="text" class="datepicker input-medium" name="bt_selesai" value="<?php echo $bt_selesai;?>" readonly></td>
				</tr>
				<tr>
					<td>Sumber Dana</td>
					<td colspan="3"><input type="text" name="sumber_dana" class="input-xxlarge" value="<?php echo $sumber_dana;?>"></td>
				</tr>
				<tr>
					<td>Jumlah Dana</td>
					<td><input type="number" name="jumlah_dana" class="input-medium" value="<?php echo $jumlah_dana;?>"></td>
					<td colspan="2">
					<div id="partner-penelitian-btn" class="link" style="cursor:pointer"><i class="icon-user"></i> Partner Pengabdian</div>
					</td>
				</tr>
				<tr id="partner-penelitian" style="display:none">
					<td>Partner Pengabdian</td>
					<td colspan="3">
						<div class="tujuan-surat">
							<div class="auto-surat grup">
								<div class="label-input">Dosen/Staff</div>
								<!-- cek nilai / edit position -->
								<?php if($dosen_partner !== ''){?>
								<ul class="token-input-list">
									<?php $list = explode('<$>',$dosen_partner); for($a=0; $a<count($list); $a++){?>
									<li class="token-input-token token-input-selected-token"><p><?php echo $list[$a];?></p><span class="token-input-delete-token">x</span></li>
									<?php } ?>
								<?php } ?>
								<input type="text" name="dosen" id="dosen" />
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
				<tr><th colspan="4">Data Beban Kerja Dosen</th></tr>
				<tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea name="jenis_kegiatan" id="kegiatan" class="input-xxlarge" required><?php echo $jns_keg;?></textarea></td>
				</tr>
				<tr>
					<td>Semester</td>
					<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('smt');?>"><?php echo $this->session->userdata('smt');?></option></select></td>
					<td>Tahun <br/>Akademik</td>
					<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option></select>
					</td>
				</tr>
				<tr>
					<td>Masa Penugasan</td>
					<td><input type="number" min="0" name="lama" id="lama" class="input-small" value="<?php echo $lama;?>" required></td>
					<td colspan="2">
						<select name="masa" id="masa" required>
							<option value="<?php echo $masa;?>"><?php echo $masa;?></option>
							<option value="Tahun">Tahun</option>
							<option value="Semester">Semester</option>
							<option value="Bulan">Bulan</option>
							<option value="Hari">Hari</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
					<td>
						<input type="hidden" name="bukti_penugasan" id="bukti">
						<p class="btn-group">
							<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label"><?php echo $bkt_penugasan;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td>SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_penugasan;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Kinerja</b><br/>Bukti Dokumen</td>
					<td>
						<input type="hidden" name="bukti_dokumen" id="bukti2">
						<p class="btn-group">
							<span class="btn btn-isi2" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari2" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" ><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="2">SKS : <input type="number" name="sks2" id="sks2" step="any" value="<?php echo $sks_bkt;?>" class="input-small" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td>Capaian (%)</td>
					<td colspan="3"><input type="number" name="capaian" id="capaian" value="<?php echo $capaian;?>" class="input-small" readonly></td>
				</tr>
				<tr>
					<td>Outcome</td>
					<td colspan="3"><textarea name="outcome" class="input-xxlarge"><?php echo $outcome;?></textarea></td>
				</tr>
				<tr>
					<td>Jumlah Jam/Semester</td>
					<td><input type="number" name="jml_jam" min="0" max="100" value="<?php echo $jml_jam;?>" class="input-small"></td>
					<td>Rekomendasi</td>
					<td>
						<select name="rekomendasi">
							<option value="<?php echo $recomendasi;?>"><?php echo $caption;?></option>
							<option value="SELESAI">A. SELESAI</option>
							<option value="LANJUTKAN">B. LANJUTKAN</option>
							<option value="BEBAN LEBIH">C. BEBAN LEBIH</option>
							<option value="LAINNYA">D. LAINNYA</option>
						</select>
					</td>
				</tr>
			</table>
		<?php } ?>
		<!-- end form SIPKD PENGABDIAN MASYARAKAT -->		

		<!-- form SIPKD PENUNJANG -->
			<?php if ($kode == 'D'){ ?>
			<!-- <table class="table table-responsive table-bordered table-hover"> -->
				<!-- <tr>
					<td>Kategori</td>
					<td colspan="3">
						<select name="pend_kategori" id="kategori" class="input-xxlarge">
							<option>-Pilih Kategori-</option>
							<?php
								foreach ($list_kategori as $key) {?>
									<option value="<?php echo $key['KD_KAT'];?>"><?php echo $key['NM_KAT'];?></option>
								<?php }
							?>
						</select>
					</td>
				</tr> -->
				<!-- <tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea textarea name="jenis_kegiatan" class="input-xxlarge" required></textarea></td>
					
				</tr>
				<tr>
					<td colspan="4"><input type="hidden" name="kd_kat" id="kd_kat"></td>
				</tr> -->
				<!-- <tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea name="jenis_kegiatan" class="input-xxlarge" required><?php echo $jns_keg;?></textarea></td>
				</tr> -->
				<!-- <tr>
					<td>Semester</td>
					<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('smt');?>"><?php echo $this->session->userdata('smt');?></option></select></td>
					<td>Tahun <br/>Akademik</td>
					<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option></select></td>
				</tr>
				<tr>
					<td>Masa Penugasan</td>
					<td><input type="number" min="0" name="lama" id="lama" class="input-small" value="<?php echo $lama;?>" required></td>
					<td colspan="2">
						<select name="masa" id="masa" required>
							<option value="<?php echo $masa;?>"><?php echo $masa;?></option>
							<option value="Tahun">Tahun</option>
							<option value="Semester">Semester</option>
							<option value="Bulan">Bulan</option>
							<option value="Hari">Hari</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
					<td>
						<input type="hidden" name="bukti_penugasan" id="bukti">
						<p class="btn-group">
							<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" ><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label"><?php echo $bkt_penugasan;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td>SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_penugasan;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Kinerja</b><br/>Bukti Dokumen</td>
					<td>
						<input type="hidden" name="bukti_dokumen" id="bukti2">
						<p class="btn-group">
							<span class="btn btn-isi2" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari2" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" ><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td>SKS : <input type="number" name="sks2" id="sks2" step="any" class="input-small" value="<?php echo $sks_bkt;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td>Capaian (%)</td>
					<td colspan="3"><input type="text" id="capaian" name="capaian" class="bogol input-small" value="<?php echo $capaian;?>" readonly></td>
				</tr>
				<tr>
					<td>Outcome</td>
					<td colspan="3"><textarea name="outcome" class="input-xxlarge"><?php echo $outcome;?></textarea></td>
				</tr>
				<tr>
					<td>Rekomendasi</td>
					<td colspan="3">
						<select name="rekomendasi">
							<option value="<?php echo $recomendasi;?>"><?php echo $caption;?></option>
							<option value="SELESAI">A. SELESAI</option>
							<option value="LANJUTKAN">B. LANJUTKAN</option>
							<option value="BEBAN LEBIH">C. BEBAN LEBIH</option>
							<option value="LAINNYA">D. LAINNYA</option>
						</select>
					</td>
				</tr> 
			</table> -->

			<table class="table table-responsive table-bordered table-hover">
	<tr><th colspan="4">Data Penunjang</th></tr>
	<tr>
		<td>Kategori</td>
		<!-- <td colspan="3"><textarea name="jenis_kegiatan" id="autocomplete" class="autocompleteB input-xxlarge" placeholder="Tulis jenis kegiatan sesuai pilihan yang ada" onblur="return auto_fill('Penelitian')" required><?php if(isset($value->JUDUL_PENELITIAN)) echo $value->JUDUL_PENELITIAN;?></textarea></td> -->
		<td colspan="3">
			<select name="pend_kategori" id="kategori" class="input-xxlarge" <?php if(ISSET($current_data)){?>
			readonly="readonly"
		<?php }?>>
				<?php
					if(ISSET($current_data)){
						foreach ($list_kategori as $key) {?>
									<?php
										if($key['KD_KAT'] == $kd_kat){?>
											<option value="<?php echo $nm_kat;?>" selected><?php echo $nm_kat;?></option>
										<?php }else{?>
											<option value="<?php echo $key['KD_KAT'];?>" disabled="disabled"><?php echo $key['NM_KAT'];?></option>
										<?php }
									?>
							<?php }	
					}else{?>
							<option>- Pilih Kategori -</option>
							<?php
								foreach ($list_kategori as $key) {?>
									<option value="<?php echo $key['KD_KAT'];?>"><?php echo $key['NM_KAT'];?></option>
								<?php }
							?>
					<?php }
				?>
			</select>
		</td>
	</tr>
	<input type="hidden" name="kd_kat" id="kd_kat" value="<?php if(ISSET($current_data)){echo $kd_kat;}?>">
	<input type="hidden" name="satuan" id="satuan" value="<?php if(ISSET($current_data)){echo $satuan;}?>">
	<!-- <tr>
		<td>Nama / jenis Kegiatan</td>
		<td colspan="3"><textarea name="jenis_kegiatan" class="input-xxlarge" id="judul" onblur="return auto_fill('Penelitian')" required><?php if(isset($value->JUDUL_PENELITIAN)) echo $value->JUDUL_PENELITIAN;?></textarea></td>
	</tr> -->
	<td>Nama/Jenis Kegiatan</td>
	<td colspan="3">
		<textarea name="jenis_kegiatan" class="input-xxlarge" required><?php if(ISSET($current_data)){
			echo $jns_keg;
		}else{
			echo $jns_keg;
		}?></textarea>
	</td>
	<!-- <tr>
		<td>Tanggal Mulai</td>
		<td width="40%"><input type="text" class="datepicker input-medium" name="bt_mulai" value="<?php if(isset($value->BT_MULAI)) echo $value->BT_MULAI; ?>" readonly></td>
		<td width="15%">Tanggal Selesai</td>
		<td><input type="text" class="datepicker input-medium" name="bt_selesai" value="<?php if(isset($value->BT_SELESAI)) echo $value->BT_SELESAI;?>" readonly></td>
	</tr> -->
	<tr>
		<td>Tempat</td>
		<td><textarea name="tempat" id="tempat" class="input-xlarge" required><?php echo $tempat;?></textarea></td>
		<td><?php if(isset($current_data)){
			if($satuan == 'SKS'){
				$satuan = 'MHS';
			}else{
				$satuan = $satuan;
			}echo "Jumlah ".$satuan;}else{?><label id="label_jumlah" name="label_jumlah"></label><?php }?>
		</td>
			
		<!-- <td id="label_jml_bln">Jumlah Bulan</td> -->
		<td><input type="number" name="jml_mhs" id="jml_mhs" class="input-small" min="0"
			value="<?php if(isset($current_data)){
					echo $jml_mhs;
					}?>" required>
		</td>
	</tr>
	<tr>
		<td>Semester</td>
		<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('smt');?>"><?php echo $this->session->userdata('smt');?></option></select></td>
		<td>Tahun <br/>Akademik</td>
		<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option></select></td>
	</tr>
	<!-- <input type="hidden" name="kd_bk" value="<?php echo $kd_bk?>"/> -->
	<tr><th colspan="4">Data Beban Kerja</th></tr>
				<!-- <tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea name="jenis_kegiatan" id="kegiatan" class="input-xxlarge" required><?php if(isset($jns_keg)) echo $jns_keg;?></textarea></td>
				</tr> -->
				
				<tr>
					<td>Masa Penugasan</td>
					<td><input type="number" min="0" name="lama" id="lama" class="input-small" value="<?php echo $lama;?>" required></td>
					<td colspan="2">
						
						<?php
							$data_masa = array("Tahun", "Semester", "Bulan", "Hari");
						?>	
							<?php
								if(ISSET($current_data)){?>
									<select name="masa" id="masa" required>
									<?php foreach ($data_masa as $key) {
										if($key == $masa){?>
											<option value="<?php echo $masa;?>" selected><?php echo $masa;?></option>
										<?php }else{?>
											<option value="<?php echo $key;?>"><?php echo $key;?></option>
										<?php }
									}?>
									</select>
								<?php }else{?>
								<select name="masa" id="masa" required>
									<option value="<?php echo $masa;?>" selected><?php echo $masa;?></option>
									<?php foreach ($data_masa as $key) {?>
											<option value="<?php echo $key;?>"><?php echo $key;?></option>
										<?php 
									}?>
								</select>
								<?php }	
							?>
					</td>
				</tr>
				<tr id="tr_beban_penugasan">
					<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
					<td>
						<input type="hidden" name="bukti_penugasan" id="bukti">
						<p class="btn-group">
							<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label">
						<?php 
							echo $bkt_penugasan;
						?></div>
					</td>
				</tr>
				<tr id="tr_sks_beban_penugasan">
					<td></td>
					<td colspan="4">SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_penugasan;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr id="tr_bukti_kinerja">
					<td><b>Kinerja</b><br/>Bukti Dokumen</td>
					<td>
						<input type="hidden" name="bukti_dokumen" id="bukti2">
						<p class="btn-group">
							<span class="btn btn-isi2" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari2" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr id="tr_sks_bukti_kinerja">
					<td></td>
					<td colspan="4">SKS : <input type="number" name="sks2" id="sks2" class="input-small" step="any" min="0" value="<?php echo $sks_bkt;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr id="tr_capaian">
					<td>Capaian (%)</td>
					<td colspan="3"><input type="text" name="capaian" id="capaian" class="input-small" value="<?php echo $capaian;?>" readonly></td>
				</tr>
				<tr id="tr_rekomendasi">
					<td>Rekomendasi</td>
					<td colspan="3">						
						<?php
							$data_rekomendasi = array("SELESAI"=>"A. SELESAI", "LANJUTKAN"=>"B. LANJUTKAN", "BEBAN LEBIH"=>"C. BEBAN LEBIH", "LAINNYA"=>"D. LAINNYA");
						?>
						<?php
						if(ISSET($current_data)){?>
								<select name="rekomendasi" id="idrekomendasi">
									<?php
										foreach ($data_rekomendasi as $key => $value) {
											if($key == $caption){?>
												<option value="<?php echo $caption;?>" selected><?php echo $value;?></option>
											<?php }else{?>
												<option value="<?php echo $key;?>"><?php echo $value;?></option>
											<?php }
										}
									?>
								</select>
							<?php }else{?>
								<select name="rekomendasi" id="idrekomendasi">
									<option value="<?php echo $recomendasi;?>" selected><?php echo $caption;?></option>
									<?php
										foreach ($data_rekomendasi as $key => $value) {?>
											<option value="<?php echo $key;?>"><?php echo $value;?></option>
										<?php }
									?>
								</select>
							<?php }
						?>
					</td>
				</tr>
			</table>
		<?php } ?>
		<!-- end form SIPKD PENUNJANG -->	
		
		<!-- form SIPKD PENUNJANG -->
			<?php if ($kode == 'F'){ ?>
			<table class="table table-condensed">
				<tr><th colspan="4">Data Narasumber/Pembicara</th></tr>
				<tr>
					<td width="30%">Tingkat</td>
					<td colspan="3">
						<select name="kd_tingkat" class="input-xxlarge" required>
						<option value="<?php echo $kd_tingkat;?>"><?php echo $nm_tingkat;?></option>
						<?php foreach ($kategori as $kat){?>
							<option value="<?php echo $kat['KD_TINGKAT'];?>"><?php echo $kat['NM_TINGKAT'];?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Judul Acara</td>
					<td colspan="3"><textarea name="judul_acara" class="input-xxlarge" id="judul" onblur="return auto_fill('Narasumber')" required><?php echo $judul;?></textarea></td>
				</tr>
				<tr>
					<td>Tanggal Mulai</td>
					<td width="40%"><input type="text" class="datepicker input-medium" name="bt_mulai" value="<?php echo $bt_mulai;?>" readonly></td>
					<td width="15%">Tanggal Selesai</td>
					<td><input type="text" class="datepicker input-medium" name="bt_selesai" value="<?php echo $bt_selesai;?>" readonly></td>
				</tr>
				<tr>
					<td>Status Narasumber</td>
					<td><select name="status_peneliti" class="input-medium" required>
						<option value="<?php echo $status_peneliti;?>"><?php echo $status_peneliti;?></option>
						<option value="UTAMA">UTAMA</option>
						<option value="PENDAMPING">PENDAMPING</option>
						<option value="PEMBANDING">PEMBANDING</option>
						<option value="PEMBAHAS">PEMBAHAS</option>
					</select></td>
					<td colspan="2" class="link" id="partner-penelitian-btn" style="cursor:pointer"><i class="icon-user"></i> Partner Narasumber/Pembicara</td>
				</tr>

				<tr id="partner-penelitian" style="display:none">
					<td>Partner Narasumber/Pembicara</td>
					<td colspan="3">
						<div class="tujuan-surat">
							<div class="auto-surat grup">
								<div class="label-input">Dosen/Staff</div>
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
										<td><input type="text" name="lain[]" class="input-grup input-xlarge"/></td>
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
					<td>Lokasi Acara</td>
					<td colspan="3"><input type="text" name="lokasi_acara" class="input-xxlarge" value="<?php echo $lokasi_acara;?>"></td>
				</tr>
				<tr>
					<td>Laman Publikasi</td>
					<td colspan="3"><input type="url" name="laman_publikasi" class="input-xxlarge" value="<?php echo $laman_publikasi;?>" placeholder="Masukkan URL"></td>
				</tr>
				<tr><th colspan="4">Data Beban Kerja</th></tr>
				<tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea name="jenis_kegiatan" id="kegiatan" class="input-xxlarge" required><?php echo $jns_keg;?></textarea></td>
				</tr>
				<tr>
					<td>Semester</td>
					<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('smt');?>"><?php echo $this->session->userdata('smt');?></option></select></td>
					<td>Tahun <br/>Akademik</td>
					<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option></select></td>
				</tr>
				<tr>
					<td>Masa Penugasan</td>
					<td><input type="number" min="0" name="lama" id="lama" class="input-small" value="<?php echo $lama;?>" required></td>
					<td colspan="2">
						<select name="masa" id="masa" required>
							<option value="<?php echo $masa;?>"><?php echo $masa;?></option>
							<option value="Tahun">Tahun</option>
							<option value="Semester">Semester</option>
							<option value="Bulan">Bulan</option>
							<option value="Hari">Hari</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
					<td>
						<input type="hidden" name="bukti_penugasan" id="bukti">
						<p class="btn-group">
							<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
						<p>
							Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label"><?php echo $bkt_penugasan;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td>SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_penugasan;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Kinerja</b><br/>Bukti Dokumen</td>
					<td>
						<input type="hidden" name="bukti_dokumen" id="bukti2">
						<p class="btn-group">
							<span class="btn btn-isi2" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari2" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" ><i class="icon-upload"></i></span>
						</p>
						<p>
							Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td>SKS : <input type="number" name="sks2" id="sks2" class="input-small" step="any" min="0" value="<?php echo $sks_bkt;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td>Capaian (%)</td>
					<td><input type="text" name="capaian" id="capaian" class="input-small" value="<?php echo $capaian;?>" readonly></td>
					<td>Rekomendasi</td>
					<td>
						<select name="rekomendasi">
							<option value="<?php echo $recomendasi;?>"><?php echo $caption;?></option>
							<option value="SELESAI">A. SELESAI</option>
							<option value="LANJUTKAN">B. LANJUTKAN</option>
							<option value="BEBAN LEBIH">C. BEBAN LEBIH</option>
							<option value="LAINNYA">D. LAINNYA</option>
						</select>
					</td>
				</tr>
			</table>
		<?php } ?>
		<!-- submit button -->
		<style>.btn.black{ color:#000;}</style>
		<table class="table">
		<tr>
			<td width="15%">&nbsp;</td>
			<td>
				<input type="submit" name="submit" value="<?php echo $btn;?>" class="btn btn-uin btn-inverse btn-small">
				<input type="reset" name="reset" value="Batal" class="btn btn-uin black btn-small">
			</td>
		</tr>
	</table>
</form>
<style>.total{font-weight:bold;}</style>
<style type="text/css">
	a.detail { color:#000; text-decoration:underline;}
	.scrollbar{
		background: none repeat scroll 0 0 #FCFCFC;
		border: none; 
		border-radius: 5px;
		box-shadow: 0 1px 0 #FFFFFF inset;
		width: 99%;
		padding: 0px;
		overflow-x: scroll;
		margin-bottom:10px;
	}
</style>
<?php
	/*echo "<pre>";
	print_r($data_remun);
	echo "<pre>";
	echo "==============<br>";
	echo "<pre>";
	print_r($nilai_kat);
	echo "<pre>";*/
?>
<h2>Data BKD Remun Bidang <?php echo ucwords($title);?></h2><h3>Tahun Akademik : <?php echo $this->session->userdata('ta');?>, Semester : 
		<?php echo $this->session->userdata('smt');?></h3>
		<div class="scrollbar">
		<table class="table table-bordered table-hover">
            <tr>
                <th rowspan="2" align="center">No.</th>
                <th rowspan="2">Jenis Kegiatan</th>
                <th colspan="3">Beban Kerja</th>
                <th rowspan="2">Masa Penugasan</th>
                <th colspan="3">Kinerja</th>
                <th rowspan="2">Rekomendasi</th>
                <th colspan="3" rowspan="2" style="border-right:none">Aksi</th>
            </tr>
            <tr>
                <th>Bukti Penugasan</th>
                <th>Nilai</th>
                <th>Satuan</th>
                <th>Bukti Dokumen</th>
                <th>Nilai</th>
                <th>Satuan</th>
            </tr>
           <?php 
                if (empty($data_remun)){
                    echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>';
                }else{
                    $no=0; $sks_beban = 0; $sks_bukti = 0;
                    foreach ($data_remun as $bp){ $no++;
                    	$sks_penugasan = (float) str_replace(",", ".", $bp->SKS_PENUGASAN);
                    	$sks_bkt = (float) str_replace(",", ".", $bp->SKS_BKT);
						/*$sks_beban = $sks_beban+$sks_penugasan;
						$sks_bukti = $sks_bukti+$sks_bkt;*/
						$sks_beban = $sks_beban+$bp->JML_SKS;
						$sks_bukti = $sks_bukti+$bp->JML_SKS;
						if($this->uri->segment(6) !== ''){
							if($this->uri->segment(6) == $bp->KD_BK) $bg = '#FFFFDD'; else $bg='';
						}
					?>
                    
					<?php
						$lihat = $bp->STATUS_PINDAH;
							if($lihat == 0){
								//$cek adalah nilai kd_kat_remun hasil konversi kd_kat
								//NANTI DIDISKUSI KAN SAMA DANANG LAGI TERUTAMA UNTUK KATEGORI PENDIDIKAN
								//SEMENTARA DI PAKE DATA JOIN DENGAN KONVERSI REMUN, DENGAN ASUMSI SEMUA YG ADA DI KATEGORI
								//PENDIDIKAN REMUN DAPAT DI PINDAH KE SERDOS
								//cttn : tf
								$cek = $bp->KD_KAT_REMUN;
							}else{
								$cek = $bp->KD_KAT;	
							}
							/*$cek = $bp->KD_KAT;*/
                            if($nilai_kat[$cek]['NILAI_KAT'] == "SKS"){
                                $kat_sks = (float)$bp->JML_SKS;
                                $sat = "MHS";
                                $jml_sks = $bp->JML_SKS." SKS, ";
                            }elseif($nilai_kat[$cek]['NILAI_KAT'] == "JUMLAH"){
                                $kat_sks = (float)$bp->JML_MHS;
                                $sat = $bp->SATUAN;
                                $jml_sks = "";
                            }
						?>
                    <tr bgcolor="<?php echo $bg;?>">
                        <td align="center"><?php echo $no;?>.</td>
                        <td><?php echo $bp->JENIS_KEGIATAN;?></td>
                        <td><?php echo $bp->BKT_PENUGASAN;?></td>
                        <td align="center"><?php echo $kat_sks;?></td>
                        <td><?php echo $bp->SATUAN;?></td>
                        <td><?php echo $bp->MASA_PENUGASAN;?></td>
                        <td><?php echo $bp->BKT_DOKUMEN;?></td>
                        <td align="center"><?php echo $kat_sks;?></td>
                        <td><?php echo $bp->SATUAN;?></td>
                        <td><?php echo $bp->REKOMENDASI;?></td>
                        <td><a href="<?php echo site_url().'bkd/dosen/remun/edit/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" class="btn btn-mini"><i class="icon icon-edit"></i> Edit</a></td>
                        <td><a onclick="return hapus('Anda yakin ingin menghapus data ini?')" href="<?php echo site_url().'bkd/dosen/remun/hapus_remun/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" class="btn btn-mini">
                        <i class="icon icon-trash"></i> Hapus</a></td>
                    </tr>
                <?php } ?>
                <!-- <tr class="total">
                    <td></td>
                    <td colspan="2">Jumlah Beban Kerja</td>
                    <td align="center"><?php echo $sks_beban?></td>
                    <td colspan="2">Jumlah Kinerja</td>
                    <td align="center"><?php echo $sks_bukti;?></td>
                    <td colspan="4"></td>
                </tr> -->
            <?php } ?>
        </table>
        </div>
        <?php $this->load->view('dosen/isi_dokumen_rbkd_remun');?>
		<?php $this->load->view('dosen/cari_dokumen_rbkd_remun');?>
		<?php $this->load->view('dosen/upload_dokumen_rbkd_remun');?>
	<!-- end -->


	</div>		
</div>
<script type="text/javascript" charset="utf-8">
      $(function(){
        setTimeout('closing_msg()', 10000)
      })

      function closing_msg(){
        $(".alert-msg").slideUp();
      }
    </script>
<!-- <p><a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/remun/tambah/'?>">Tambah</a></p>
 -->