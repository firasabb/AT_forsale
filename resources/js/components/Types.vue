<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col">
                <div class="card">
                    <div class="card-header">Please Choose Your {{ obj }} Type</div>
                    <div class="card-body">
                        <div class="row">
                            <div v-for="type in parsedTypes" class="col" >
                                <div class="select-types">
                                    {{ type }}
                                    {{ type.name }}
                                </div>
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
        computed: {
            parsedTypes:
                function(){
                    return this.types = JSON.parse(this.types);
                }
        }, 
        mounted: function(){
            
        }
    }
</script>
