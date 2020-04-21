<template>
    
    <div class="card">
        <div class="card-header add-card-header">Please Select Your {{ obj }} Category</div>
        <div class="card-body">
            <div class="row">
                <div v-for="category in parsedCategories" class="col" >
                    <div class="select-category">
                        <div class="card card-bottom card-inverse text-bottom" v-on:click="selectCategory($event, category.url)">
                            <img v-if="category.medias[0] != 'undefined' && category.medias[0] != null" v-bind:src="category.medias[0].public_url" class="category-img">
                            <div v-else class="no-img" v-bind:style="{ backgroundColor: randomColor() }">
                            </div>
                            <div class="card-img-overlay">
                            </div>
                            <div class="card-text-overlay" style="[category.medias[0] != 'undefined' && category.medias[0] != null ? {''} : {'top': '50%'}]">
                                <h5 class="card-title">{{ category.name.toUpperCase() }}</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            
</template>

<script>
    export default {
        props: [
            'obj',
            'categories'
        ],
        data: function () {
            return{
                colors: ['#70ffa1', '#ffef70', '#ff8b70', '#d970ff', '#ff7070'],
            }
        },
        computed: {
            parsedCategories:
                function(){
                    console.log(this.categories);
                    return JSON.parse(this.categories);
                },
        }, 
        methods: {
            randomColor: function (){
                return this.colors[Math.floor(Math.random() * this.colors.length)];
            },
            selectCategory: function(event, url){
                window.location.href += '/' + url;
            }
        }
    }
</script>
