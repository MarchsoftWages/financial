<template>
    <div class="header">
        <x-header>反馈</x-header>
    </div>
</template>
<style scoped>
    .header{
        z-index: 9;
        width: 100%;
        height: 600px;
        top: 0;
        background: #f1f1f1;
        overflow: hidden;
    }
</style>
<script>
    import {XHeader} from 'vux'
    export default{
        components:{
            XHeader,
        },
    }
</script>