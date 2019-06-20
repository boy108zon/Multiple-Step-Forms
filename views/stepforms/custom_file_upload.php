<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/basic.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/dropzone.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<style>
    #myOverlay {height:100%;width:100%;position:absolute;top:0px;left:0px;}
    #myOverlay {background:black;opacity:0.5;z-index:10;display:none;}
    #box {position:absolute;top:150px;left:125px;height:200px;width:550px;background:white;z-index:15;display:none;}
    #box-inside-div{margin-left:20px;margin-top:30px;} 
    .ajax-upload-dragdrop{
        border: none;
        padding: 0px 0px 0;

    }
    .ajax-file-upload{
        font-size:14px;
        font-weight: normal;
    }
    /* Mimic table appearance */
    .container1{
        width: 100%;
    }
    .container1{
        padding-right: 15px;
        padding-left: 15px;
        margin-right: auto;
        margin-left: auto;
    }
    .table{
        width: 100%;
        margin: 0px;
    }
    .progress-bar{
        width: 48%;
    }
    div.table {
        display: table;
    }
    div.table .file-row {
        display: table-row;
    }
    div.table .file-row > div {
        display: table-cell;
        vertical-align: top;
        border-top: 1px solid #ddd;
        padding: 8px;
    }
    div.table .file-row:nth-child(odd) {
        background: #f9f9f9;
    }
    /* The total progress gets shown by event listeners */
    #total-progress {
        opacity: 0;
        transition: opacity 0.3s linear;
    }
    /* Hide the progress bar when finished */
    #previews .file-row.dz-success .progress {
        opacity: 0;
        transition: opacity 0.3s linear;
    }
    /* Hide the delete button initially */
    #previews .file-row .delete {
        display: none;
    }
    /* Hide the start and cancel buttons and show the delete button */
    #previews .file-row.dz-success .start,
    #previews .file-row.dz-success .cancel {
        display: none;
    }
    #previews .file-row.dz-success .delete {
        display: block;
    }

</style>
<div id="myOverlay"></div>
<div class="modal-header">
    <button type="button" class="close myclosebtnmp" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">X</span></button>
    <h4 class="modal-title">Upload A Document</h4>
</div>
<div class="modal-body">
    <div class="faster-ajax-loader faster_ajax_loader" style="display:none;"></div>
    <div class="popup_message"></div>
    <div class="container1">
        <input type="hidden" name="m_form_id" id="m_form_id" value="<?php echo isset($form_id) ? $form_id : ''; ?>"/>
        <input type="hidden" name="m_form_process_id" id="m_form_process_id" value="<?php echo isset($form_process_id) ? $form_process_id : ''; ?>"/>
        <input type="hidden" name="m_step_id" id="m_step_id" value="<?php echo isset($step_id) ? $step_id : ''; ?>"/>
        <input type="hidden" name="m_doc_key" id="m_doc_key" value="<?php echo isset($doc_key) ? $doc_key : ''; ?>"/>

        <div id="actions" class="row">
            <div class="col-lg-12">
                <span class="btn btn-success fileinput-button dz-clickable">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>Add files...</span>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start upload</span>
                </button>
                <button type="reset" class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
            </div>

            <div class="col-lg-5">
                <!-- The global file processing state -->
                <span class="fileupload-process">
                    <div id="total-progress" class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress=""></div>
                    </div>
                </span>
            </div>
        </div>
        <div class="table table-striped files" id="previews">
            <div id="template" class="file-row dz-image-preview">
                <!-- This is used as the file preview template -->
                <div>
                    <span class="preview"><img data-dz-thumbnail></span>
                </div>
                <div>
                    <p class="name" data-dz-name></p>
                    <strong class="error text-danger" data-dz-errormessage></strong>
                </div>
                <div>
                    <p class="size" data-dz-size></p>
                    <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0">
                        <div class="progress-bar progress-bar-success" style="width:0%;" data-dz-uploadprogress></div>
                    </div>
                </div>
                <div>
                    <button class="btn btn-primary start">
                        <i class="glyphicon glyphicon-upload"></i>
                        <span>Start</span>
                    </button>
                    <button data-dz-remove class="btn btn-warning cancel">
                        <i class="glyphicon glyphicon-ban-circle"></i>
                        <span>Cancel</span>
                    </button>
                    <button data-dz-remove class="btn btn-danger delete">
                        <i class="glyphicon glyphicon-trash"></i>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" id="submit-all" class="btn btn-danger pull-left">Allowed Images: pdf,doc,docx  Max Size: 5 MB</button>
</div>
<script>
    $(document).ready(function () {
        var previewNode = document.querySelector("#template");
        previewNode.id = "";
        var previewTemplate = previewNode.parentNode.innerHTML;
        previewNode.parentNode.removeChild(previewNode);
        var myDropzoneOut='';
        $("#myaddcustomebanner").on("shown.bs.modal", function () {
           var myDropzone = new Dropzone(".container1", {
                url: "<?php echo base_url() . 'admin/form/do_upload_form_document'; ?>",
                thumbnailWidth: 80,
                thumbnailHeight: 80,
                parallelUploads: 1,
                addRemoveLinks: false,
                previewTemplate: previewTemplate,
                autoQueue: false,
                previewsContainer: "#previews",
                clickable: ".fileinput-button",
                acceptedFiles: 'application/pdf,application/xls,application/excel,application/vnd.ms-excel,application/vnd.ms-excel; charset=binary,application/msexcel,application/x-excel,application/x-msexcel,application/x-ms-excel,application/x-dos_ms_excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                init: function () {
                    $('.dz-image-preview').remove();
                    thisDropzone = this;
                    thisDropzone.on('maxfilesexceeded', function (file) {
                        thisDropzone.removeAllFiles();
                        thisDropzone.addFile(file);
                    });
                    var file = {};
                    thisDropzone.on("success", function (file, response) {
                        var resC = JSON.parse(response);
                        file['name'] = resC.name;
                        file['size'] = resC.size;
                        file['id'] = resC.id;
                    });
                    thisDropzone.on("removedfile", function (file, response) {
                        $.ajax({
                            url: '<?php echo base_url() . 'admin/form/do_remove_form_document/'; ?>',
                            type: "POST",
                            dataType: 'json',
                            data: {'bid': file.id, op: "remove"},
                            success: function (response) {
                                $(".popup_message").html(response.msg);
                                $(".showmessage_jscbf_File").fadeOut("slow");
                            }
                        });
                    });

                    $.ajax({
                        url: '<?php echo base_url() . 'admin/form/list_all'; ?>',
                        type: 'POST',
                        dataType: 'json',
                        data: {'form_id': encodeURIComponent($("#m_form_id").val()), 'form_process_id': encodeURIComponent($("#m_form_process_id").val()), 'step_id': encodeURIComponent($("#m_step_id").val()), 'doc_key': encodeURIComponent($("#m_doc_key").val())},
                        success: function (mockFile) {
                            if (mockFile.length > 0) {
                                $.each(mockFile, function (key, value) {
                                    var mockFile = {name: value.name, size: value.size, id: value.doc_id}
                                    mockFile.accepted = true;
                                    thisDropzone.emit('addedfile', mockFile)
                                    thisDropzone.emit('thumbnail', mockFile, 'http://someserver.com/myimage.jpg')
                                    thisDropzone.emit('complete', mockFile)
                                    mockFile.previewElement.classList.add('dz-success');
                                    mockFile.previewElement.classList.add('dz-complete');
                                });
                            }
                        }
                    });
                }
            });
            myDropzoneOut=myDropzone;
            myDropzone.on("addedfile", function (file) {
                file.previewElement.querySelector(".start").onclick = function () {
                    myDropzone.enqueueFile(file);
                };
            });
            myDropzone.on("totaluploadprogress", function (progress) {
                document.querySelector("#total-progress .progress-bar").style.width = progress + "%";
            });
            myDropzone.on("sending", function (file, xhr, formData) {
                document.querySelector("#total-progress").style.opacity = "1";
                formData.append("m_form_id", $("#m_form_id").val());
                formData.append("m_form_process_id", $("#m_form_process_id").val());
                formData.append("m_step_id", $("#m_step_id").val());
                formData.append("m_doc_key", $("#m_doc_key").val());
                file.previewElement.querySelector(".start").setAttribute("disabled", "disabled");
            });
            myDropzone.on("queuecomplete", function (progress) {
                document.querySelector("#total-progress").style.opacity = "0";
            });
            document.querySelector("#actions .start").onclick = function () {
                myDropzone.enqueueFiles(myDropzone.getFilesWithStatus(Dropzone.ADDED));
            };
            document.querySelector("#actions .cancel").onclick = function () {
                myDropzone.removeAllFiles(true);
            };
        }).modal('show');
        $('#myaddcustomebanner').on('hidden.bs.modal', function () {
            //myDropzone.removeAllFiles(true);
            //myDropzoneOut.off();
        });
    });
</script>
