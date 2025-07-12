<script setup lang="ts">
import Detail from '../components/Detail.vue';
import { useRoute } from 'vue-router';

const props = defineProps<{ id: string }>();
const route = useRoute();
const id = route.params.id as string;

</script>

<template>
  <main>
    <Detail :id="id" />
  </main>
</template>
