<style>
	thead, tbody, tfoot, tr, td, th {
		border-color: inherit;
		border-style: none;
		border-width: 0;
	}
    body { background: #bbbdc0; }
</style>
<div class="main_container" style="padding: 20px;background: #bbbdc0;">
    <div class="tts_row">
        <button type="button" class="badge badge-md badge-primary" onclick="print_stvinv('print_stvinv');" style="position: absolute;right: 0;margin-right: 25px;">Print</button>
    </div>
    <div id="print_stvinv">
        <?php echo $data; ?>
    </div>
</div>
<script>
    setTimeout(function(){
         print_stvinv('print_stvinv');
    }, 2000); 

    //** * *********************************  Print Function   ***************************** *//
function print_stvinv(divName) {
   
   var printContents = document.getElementById(divName).innerHTML;
   var originalContents = document.body.innerHTML;
   document.body.innerHTML = printContents;
   window.print();
   document.body.innerHTML = originalContents;
}

//** * *********************************   Print Function    ***************************** *//
</script>

