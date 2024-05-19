<script setup lang="ts">

defineProps({
    text: {
        type: String,
        required: true
    }
});

</script>

<template>
    <div class="nice-delimiter">
      <span class="line"></span>
      <span class="text">{{ text }}</span>
      <span class="line"></span>
    </div>
</template>

<style scoped>
.nice-delimiter {
  display: flex;
  align-items: center;
  margin: 10px 0;
}

.line {
  flex: 1;
  height: 1px;
  background-color: #ccc;
}

.text {
  margin: 0 10px;
  color: #888;
  text-transform: uppercase;
  letter-spacing: 2px;
  font-size: 12px;
  font-family: 'Roboto-500';
}
</style>
