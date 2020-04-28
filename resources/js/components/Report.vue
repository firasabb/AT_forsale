<template>
    <div class="modal fade" id="reportModal" tabindex="-1" role="dialog" aria-labelledby="reportModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <form method="POST">
                    {!! csrf_field() !!}
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Report This Asset</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <textarea class="form-control" name="body" placeholder="Please describe why the asset should not be on the website..."></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button name="action" type="submit" class="btn btn-primary">Submit</button>
                </div>
                <input type="hidden" name="_q">
            </form>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted(){
            console.log('as');
        }
    }
</script>
