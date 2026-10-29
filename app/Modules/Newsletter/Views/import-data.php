<div class="content">
    <div class="page-content">
      <div class="table_title">
            <div class="sale_bar">
                <div class="tts_row">
                    <div class="tts-col-2">
                        <span> Import Data</span>
                    </div>
                    <div class="tts-col-10 text_right">
                       
                    </div>
                </div>
            </div>
        </div>
        <div class="page-content-area">
                <div class="card-body">
                    <div class="tts_row mb_10">
                        <div class="tts-col-6">
                            <h6>Field mapping</h6>
                            <p><b> Note: </b> Please map field value carefully</p>                    
                            <div class="responcive_table">
                                
                            <form action="<?php echo site_url();?>newsletter/import-data?filename=<?php echo $_GET['filename']; ?>" method="post">
                                <table class="table-strip divName">
                                        <tr>
                                            <th>DB Field Name</th>
                                            <th>Field In File</th>
                                        </tr>
                                        <?php if($dbfields) {
                                            foreach($dbfields as $field) { ?>
                                                <tr>
                                                    <td><?php echo ucfirst(str_replace('_', ' ', $field)); ?></td>
                                                    <td> 
                                                        <select class="form-control" name="<?php echo $field;?>">
                                                            <option value="">Choose columns</option>
                                                                <?php if($filefields) {
                                                                foreach($filefields as $key=>$filefield) { ?>
                                                                        <option value="<?php echo $key; ?>"><?php echo $key; ?> - <?php echo $filefield;?> </option>
                                                            <?php }  } ?>
                                                        </select>
                                                    </td>
                                                </tr>
                                        <?php  } } ?>
                                </table>

                                <div class="tts_row">
                                    <div class="tts-col-12 mt8">
                                       <button type="submit" class="badge badge-md badge-primary pull-right">Save</button>
                                    </div>
                                </div>
                               
                            </form>    
                            </div>
                        </div>

                        <div class="tts-col-6">
                            <h6>Preview data</h6>
                            <p><b> File Name: </b> <?php echo $fileinfo['name'];?>, <b> File Size: </b> <?php echo formatBytes($fileinfo['size']);?>, <b> Total records: </b> <?php echo count($allDataInSheet);?></p>
                        
                            <div class="responcive_table">
                                <table class="table-strip divName">
                                    <?php if($allDataInSheet) { 
                                        foreach(array_slice($allDataInSheet, 0, 5) as $dataInSheet) {
                                        ?>
                                        <tr>
                                            <?php if($dataInSheet) {
                                                foreach($dataInSheet as $value) {
                                             ?>
                                            <td><?php echo $value;?></td>
                                            <?php } } ?>
                                        </tr>
                                    <?php } } ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>    
        </div>
    </div>
</div>
