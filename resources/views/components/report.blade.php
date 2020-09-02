<div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <form method="POST" v-bind:action="route">
                {!! csrf_field() !!}
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">{{ __('main.report') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <textarea class="form-control" name="body" placeholder="{{ __('main.Descript Report Cause') }}"></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('main.close') }}</button>
                <button name="action" type="submit" class="btn btn-primary">{{ __('main.submit') }}</button>
            </div>
            <input type="hidden" name="_q" v-bind:value="id">
        </form>
        </div>
    </div>
</div>