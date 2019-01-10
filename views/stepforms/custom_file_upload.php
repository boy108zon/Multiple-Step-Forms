<link href="<?php echo base_url(); ?>assets/dist/uploader/uploadfile.css" rel="stylesheet">
<script src="<?php echo base_url(); ?>assets/dist/uploader/jquery.uploadfile.js"></script>
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
</style>
<div id="myOverlay"></div>
<div class="modal fade" tabindex="-1" role="dialog" id="myaddcustomebanner" data-backdrop="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span></button>
                <h4 class="modal-title">Upload A Document</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" name="m_form_id" id="m_form_id" value=""/>
                <input type="hidden" name="m_form_process_id" id="m_form_process_id" value=""/>
                <input type="hidden" name="m_step_id" id="m_step_id" value=""/>
                <input type="hidden" name="m_doc_key" id="m_doc_key" value=""/>
                
                <div class="faster-ajax-loader faster_ajax_loader" style="display:none;"></div>
                <div class="popup_message"></div>
                <div class="b-form-row">
                        <div id="mulitplefileuploader">Browse</div>
                </div>
                <div class="document-container">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger pull-left">Allowed Images: pdf,doc,docx  Max Size: 5 MB</button>
            </div>
        </div>
    </div>
</div>
<script>

    $(document).ready(function () {
        var settings = {
            url: "<?php echo base_url() . 'admin/form/do_upload_form_document'; ?>",
            method: "POST",
            allowedTypes: "pdf,doc,docx",
            fileName: "userfile",
            showCancel: true,
            dragDrop: true,
            showAbort: true,
            autoSubmit: true,
            abortStr: true,
            showDone: false,
            cancelStr: "Cancel",
            multiple: false,
            returnType: "json",
            showDelete: true,
            dynamicFormData: function () {
                var m_fid = document.getElementById("m_form_id");
                var m_form_id = m_fid.value;
                
                var m_fpid = document.getElementById("m_form_process_id");
                var m_fp_id = m_fpid.value;
                
                var m_sid = document.getElementById("m_step_id");
                var m_s_id = m_sid.value;
                
                var m_dk = document.getElementById("m_doc_key");
                var m_dkey = m_dk.value;
                
                
                var data = {m_form_id: m_form_id,m_form_process_id:m_fp_id,m_step_id:m_s_id,m_doc_key:m_dkey}
                return data;
            },
            deleteCallback: function (data, pd) {
                for (var i = 0; i < data.length; i++) {
                    $.post("<?php echo base_url() . 'admin/form/do_remove_form_document'; ?>", {op: "remove", bid: encodeURIComponent(data[i])},
                    function (resp, textStatus, jqXHR) {
                        $(".popup_message").html('<div class="showmessage_jscbf_File alert alert-success">Removed successfully</div>');
                        $(".showmessage_jscbf_File").fadeOut("slow");
                    });
                }
                pd.statusbar.hide(); //You choice.
            },
            onSelect: function (files) {

                return true; //to allow file submission.
            },
            onSuccess: function (files, data, xhr) {
                switch (data.Mstatus) {
                    case 'success':
                        $(".popup_message").html("");
                        $(".popup_message").html('<div class="alert alert-success">Uploaded successfully</div>');
                        break;
                    case 'error':
                        $(".popup_message").html(data.msg);
                        break;
                    default:
                        break;
                }
            },
            afterUploadAll: function () {

            },
            onError: function (files, status, errMsg) {
                alert(errMsg);
            }
        }
        var uploadObj = {}
        $('#myaddcustomebanner').on('shown.bs.modal', function () {
            uploadObj = $("#mulitplefileuploader").uploadFile(settings);
        })
        $('#myaddcustomebanner').on('hidden.bs.modal', function () {
            uploadObj.remove();
        })
    });
</script>