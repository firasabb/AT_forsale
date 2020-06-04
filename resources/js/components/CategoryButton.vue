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
                let goTo =  window.location.protocol + '//' + window.location.hostname + '/categories/' + url;
                window.open(goTo, '_blank');
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