<div id="callBack" style="display: none;" data-loaded="1" class="sleadYear">
    <div class="bootbox modal fade bootbox-alert in" tabindex="-1" role="dialog" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="bootbox-body">
                        <div class="heading">
                            <span>Сайт содержит контент 18+</span>
                        </div>
                        <div class="yText">Для просмотра сайта, подтвердите, что Вам не менее 18 лет.</div>
                        <div class="yButton">
                            <a class="btn btn-default" id="accessOk" href="javascript:void(0);">Да, мне 18+</a>
                            <a class="btn btn-primary pull-right" id="accessFalse" href="javascript:parent.jQuery.fancybox.close();">Мне меньше 18</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade in"></div>
</div>
<script>
    $(document).ready(function() {
        $('#accessOk').click(function(){
            $.cookie("accsess2173", "ok", { expires: 7 });
            yearOld.close();
        });
        $('#accessFalse').click(function(){
            window.location = 'http://google.com/';
        });
    });
</script>