<script setup lang="ts">
  import { useQuery } from '@tanstack/vue-query';
  import axios from 'axios';
  import { ref, type Ref } from 'vue';
  import { EyeFilled } from '@ant-design/icons-vue';
  import { useRouter } from 'vue-router';


  const router = useRouter();

  const type = ref('');
  const name = ref('');
  const page = ref(1);

  const fetchListPokemon = async (name: Ref<string>, type: Ref<string>, page: Ref<number>) => {
    const result = await axios.get('http://localhost:84/list', {
      params: {
        name: name.value,
        type: type.value,
        page: page.value,
      }
    });
    
    return result.data;
  }

  const {data, isLoading, isError} = useQuery({ 
    queryKey: ['listPokemon', name, type, page], 
    queryFn: () => fetchListPokemon(name, type, page)
  });

  const columns: object[] = [
  {
    title: '#ID',
    dataIndex: 'key',
    key: 'key',
  },
  {
    title: 'Nome',
    dataIndex: 'name',
    key: 'name',
  },
  {
    title: 'Tipo',
    dataIndex: 'type',
    key: 'type',
  },
  {
    title: 'Detalhes',
    dataIndex: 'detail',
    key: 'detail',
  },
  ];
  

  const transformData = () => {
    const dataSource: object[] = [];

    if (!data.value)  return [];

    data.value.data.forEach((pokemon: any) => {
      dataSource.push({
        key: pokemon.id,
        name: pokemon.name,
        // height: pokemon.height * 10,
        // weight: pokemon.weight / 10,
        type: pokemon.type,
      })
    });

    return dataSource;
  }

  const next = () => {
    page.value++;
  }

  const prev = () => {
    if (page.value > 1)
      page.value--;
  }

  
</script>

<template>
  <div class="mb-4">
    <span>Filtros</span>
  </div>
  <div class="flex mb-4 justify-between items-center">
    <a-input class="flex-1" v-model:value="name" placeholder="Name" />
    <a-input class="flex-1" v-model:value="type" placeholder="Tipo" />
  </div>
  <div>
    <a-table 
      :dataSource="transformData()" 
      :columns="columns"
      :pagination="false"
      :loading="isLoading"
    >
      <template #bodyCell="{ column, record }">
        <template v-if="column.key === 'detail'">
          <a-button type="dashed" class="!flex items-center justify-center" @click="router.push(`/detail/${record.key}`)">
            <template #icon>
              <EyeFilled />
            </template>
          </a-button>
      </template>
      </template>
    </a-table>
  </div>
  <div class="flex justify-end mt-4">
    <a-button class="mr-2.5 ml-2.5" type="primary" @click="prev">Anterior</a-button>
    <a-button class="mr-2.5 ml-2.5" type="primary" @click="next">Próximo</a-button>
  </div>
</template>
