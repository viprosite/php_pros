<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div  @click='say'>Example Component</div>
                    <button v-on:click="say" class="btn btn-danger">btn</button>
                    <div class="card-body">
                        I'm an example component111.1111
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name:'exam',
        mounted() {
            console.log('Component mounted.')
        },
        methods:{
            say:function () {
                alert()
            }
        }
    }
</script>
