<template>
    
    <div class="card">
        <div class="card-header add-card-header">Please Select Your {{ obj }} Type</div>
        <div class="card-body">
            <div class="row">
                <div v-for="type in parsedTypes" class="col" >
                    <div class="select-type">
                        <div class="card card-bottom card-inverse text-bottom" v-on:click="selectType($event, type.url)">
                            <img v-if="type.medias[0] != 'undefined' && type.medias[0] != null" v-bind:src="type.medias[0].public_url" class="type-img">
                            <div v-else class="no-img" v-bind:style="{ backgroundColor: randomColor() }">
                            </div>
                            <div class="card-img-overlay">
                            </div>
                            <div class="card-text-overlay" style="[type.medias[0] != 'undefined' && type.medias[0] != null ? {''} : {'top': '50%'}]">
                                <h5 class="card-title">{{ type.name.toUpperCase() }}</h5>
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
            'types'
        ],
        data: function () {
            return{
                colors: ['#70ffa1', '#ffef70', '#ff8b70', '#d970ff', '#ff7070'],
            }
        },
        computed: {
            parsedTypes:
                function(){
                    return JSON.parse(this.types);
                },
        }, 
        methods: {
            randomColor: function (){
                return this.colors[Math.floor(Math.random() * this.colors.length)];
            },
            selectType: function(event, url){
                window.location.href += '/' + url;
            }
        },
        mounted: function(){
            
        }
    }
</script>
