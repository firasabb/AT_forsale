<template>
    <span :class="['card-category-span card-category-' + parsedCategory.id]" v-on:click="goToCategory(parsedCategory.url)">{{ parsedCategory.name.toUpperCase() }}</span>
</template>

<script>
    export default {
        props: [
            'backgroundColor',
            'category'
        ],
        computed: {
            parsedCategory:
                function(){
                    return JSON.parse(this.category);
                },
        }, 
        methods: {
            goToCategory: function(url){
                window.location.href =  window.location.protocol + window.location.hostname + '/category/' + url;
            },
        },
        mounted: function(){
            var parsedCategory = this.parsedCategory;
            var backgroundColor = this.backgroundColor;
            $(document).ready(function(){
                $('.card-category-' + parsedCategory.id).hover(
                    function(){
                        $(this).css('background-color', backgroundColor);
                    }, function(){
                        $(this).css('background-color', 'transparent');
                    }
                );
            });
        }
    }
</script>