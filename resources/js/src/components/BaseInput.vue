<script setup lang="ts">
    defineProps<{
        type?: string,
        modelValue: string
    }>()

    const emit = defineEmits<{
        (e: 'update:modelValue', val:any):void
    }>()

    function updateValue(event: any) {
        const val = event.target.value;
        emit('update:modelValue', val);
    }
</script>
<template>
    <input
        :value="modelValue"
        :type="typeof type==='undefined' ? 'text' : type"
        class="border rounded-md mt-1 p-2 w-full"
        @input="updateValue"
        placeholder=""
    >
</template>