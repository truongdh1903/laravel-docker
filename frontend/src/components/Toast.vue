<template>
    <div :class="'toast ' + (message == 'Success' ? 'bg-success' : 'bg-danger')" style="position: absolute; top: 20px; right: 20px; z-index: 999;">
        <div class="toast-header">
            <strong class="mr-auto">Notification</strong>
        </div>
        <div class="toast-body">
            {{ message }}
        </div>
    </div>
</template>
<script>
export default {
    name: 'Toast',
    props: {
        message: String
    }
}
</script>
<style scoped>
    
</style>