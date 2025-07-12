<script setup lang="ts">
  import { useQuery } from '@tanstack/vue-query';
  import axios from 'axios';
  import { BarsOutlined } from '@ant-design/icons-vue';
  import { useRouter } from 'vue-router';
  
  const router = useRouter();

  const props = defineProps({
    id: {
      type: String,
      required: true
    }
  })

  const fetchDetailPokemon = async (id: String) => {
    const result = await axios.get(`http://localhost:84/detail/${id}`);
    
    return result.data;
  }

  const {data, isLoading, isError} = useQuery({ 
    queryKey: ['listPokemon', props.id], 
    queryFn: () => fetchDetailPokemon(props.id)
  });
  
</script>

<template>
  <div class="flex justify-end">
    <a-button type="primary" class="!flex items-center justify-center" @click="router.push('/')">
        <template #icon>
          <BarsOutlined />
        </template>Listagem
      </a-button>
  </div>
  <div v-if="isLoading">
    <a-skeleton />
  </div>
  <div v-else>
    <a-descriptions title="Informações do Pokemon" layout="vertical">
      <a-descriptions-item label="Name">{{data.name}}</a-descriptions-item>
      <a-descriptions-item label="Altura">{{data.height}}</a-descriptions-item>
      <a-descriptions-item label="Peso">{{data.weight}}</a-descriptions-item>
      <a-descriptions-item label="Tipo">{{data.type}}</a-descriptions-item>
    </a-descriptions>
  </div>
</template>
