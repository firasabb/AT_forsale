<template>
<div class="col-6">
    <div class="link-container">
        <div class="form-row">
            <div class="col-6">
                <select class="form-control" v-model="selectName">
                    <option name="facebook">Facebook</option>
                    <option name="instagram" selected>Instagram</option>
                    <option name="Github">Github</option>
                    <option name="website">Website</option>
                    <option name="blog">Blog</option>
                    <option name="custom">Custom</option>
                </select>
            </div>
            <div class="col-6">
                <input class="form-control" :name="selectName">
            </div>
        </div>
    </div>
</div>
</template>
<script>
    export default {
        data: function(){
            return{
                selectName: ''
            }
        }
    }
</script>