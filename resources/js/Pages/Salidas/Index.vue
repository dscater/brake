<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Salidas",
        disabled: false,
        url: "",
        name_url: "",
    },
];
</script>
<script setup>
import BreadBrums from "@/Components/BreadBrums.vue";
import { useApp } from "@/composables/useApp";
import { useMenu } from "@/composables/useMenu";
import { Head, usePage } from "@inertiajs/vue3";
import { useSalidas } from "@/composables/salidas/useSalidas";
import { ref, onMounted } from "vue";
const { mobile, identificaDispositivo, cambiarUrl } = useMenu();
const { setLoading } = useApp();
const { props } = usePage();
onMounted(() => {
    identificaDispositivo();
    setTimeout(() => {
        setLoading(false);
    }, 300);
});

const { getSalidasApi, deleteSalida } = useSalidas();
const responseSalidas = ref([]);
const listSalidas = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
        sortable: false,
    },
    { title: "Tipo de Salida", align: "start", sortable: false },
    { title: "Unidad Solicitante", align: "start", sortable: false },
    { title: "Descripción", align: "start", sortable: false },
    { title: "Fecha de Salida", align: "start", sortable: false },
    { title: "Fecha de Registro", align: "start", sortable: false },
    { title: "Más", align: "start", sortable: false },
    { title: "Acción", align: "end", sortable: false },
]);

const search = ref("");
const options = ref({
    page: 1,
    itemsPerPage: itemsPerPage,
    sortBy: "",
    sortOrder: "desc",
    search: "",
});

const loading = ref(true);
const totalItems = ref(0);
let setTimeOutLoadData = null;
const loadItems = async ({ page, itemsPerPage, sortBy }) => {
    loading.value = true;
    options.value.page = page;
    if (sortBy.length > 0) {
        options.value.sortBy = sortBy[0].key;
        options.value.sortOrder = sortBy[0].order;
    }
    options.value.search = search.value;

    clearInterval(setTimeOutLoadData);
    setTimeOutLoadData = setTimeout(async () => {
        responseSalidas.value = await getSalidasApi(options.value);
        listSalidas.value = responseSalidas.value.data;
        totalItems.value = parseInt(responseSalidas.value.total);
        loading.value = false;
    }, 300);
};
const recargaSalidas = async () => {
    loading.value = true;
    listSalidas.value = [];
    options.value.search = search.value;
    responseSalidas.value = await getSalidasApi(options.value);
    listSalidas.value = responseSalidas.value.data;
    totalItems.value = parseInt(responseSalidas.value.total);
    setTimeout(() => {
        loading.value = false;
    }, 300);
};

const editarSalida = (item) => {
    cambiarUrl(route("salidas.edit", item.id));
};

const verSalida = (item) => {
    cambiarUrl(route("salidas.show", item.id));
};

const pdf = (item) => {
    window.open(route("salidas.pdf", item.id), "_blank");
};

const eliminarSalida = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.nombre}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deleteSalida(item.id);
            if (respuesta && respuesta.sw) {
                recargaSalidas();
            }
        }
    });
};
const verUbicación = async (item) => {};
</script>
<template>
    <Head title="Salidas"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    v-if="props.auth.user.permisos.includes('salidas.create')"
                    color="primary"
                    prepend-icon="mdi-plus"
                    @click="cambiarUrl(route('salidas.create'))"
                >
                    Agregar</v-btn
                >
            </v-col>
        </v-row>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-card flat>
                    <v-card-title>
                        <v-row class="bg-primary d-flex align-center pa-3">
                            <v-col cols="12" sm="6" md="4"> Salidas </v-col>
                            <v-col cols="12" sm="6" md="4" offset-md="4">
                                <v-text-field
                                    v-model="search"
                                    label="Buscar"
                                    append-inner-icon="mdi-magnify"
                                    variant="underlined"
                                    clearable
                                    hide-details
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </v-card-title>
                    <v-card-text>
                        <v-data-table-server
                            v-model:items-per-page="itemsPerPage"
                            :headers="!mobile ? headers : []"
                            :class="[mobile ? 'mobile' : '']"
                            :items-length="totalItems"
                            :items="listSalidas"
                            :loading="loading"
                            :search="search"
                            @update:options="loadItems"
                            height="auto"
                            no-data-text="No se encontrarón registros"
                            loading-text="Cargando..."
                            page-text="{0} - {1} de {2}"
                            items-per-page-text="Registros por página"
                            :items-per-page-options="[
                                { value: 10, title: '10' },
                                { value: 25, title: '25' },
                                { value: 50, title: '50' },
                                { value: 100, title: '100' },
                                {
                                    value: -1,
                                    title: 'Todos',
                                },
                            ]"
                        >
                            <template v-slot:item="{ item }">
                                <template v-if="!mobile">
                                    <tr>
                                        <td>{{ item.id }}</td>
                                        <td>{{ item.tipo_salida.nombre }}</td>
                                        <td>{{ item.unidad_solicitante }}</td>
                                        <td>{{ item.descripcion }}</td>
                                        <td>{{ item.fecha_salida_t }}</td>
                                        <td>{{ item.fecha_registro_t }}</td>
                                        <td>
                                            <v-btn
                                                :icon="
                                                    item.mas
                                                        ? 'mdi-chevron-down'
                                                        : 'mdi-chevron-left'
                                                "
                                                @click="item.mas = !item.mas"
                                            ></v-btn>
                                        </td>
                                        <td class="text-right" width="5%">
                                            <v-btn
                                                color="yellow-accent-2"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="pdf(item)"
                                                icon="mdi-file-outline"
                                            ></v-btn>
                                            <v-btn
                                                color="primary"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="verSalida(item)"
                                                icon="mdi-eye-circle"
                                            ></v-btn>
                                            <!-- <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'salidas.edit'
                                                    )
                                                "
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="editarSalida(item)"
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'salidas.destroy'
                                                    )
                                                "
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="eliminarSalida(item)"
                                                icon="mdi-trash-can"
                                            ></v-btn> -->
                                        </td>
                                    </tr>
                                    <tr v-if="item.mas">
                                        <td
                                            :colspan="headers.length"
                                            class="py-5"
                                        >
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center"
                                                >
                                                    <v-table class="border">
                                                        <thead>
                                                            <tr>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    N°
                                                                </th>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    Producto
                                                                </th>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    Cantidad
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr
                                                                v-for="(
                                                                    item, index
                                                                ) in item.salida_detalles"
                                                            >
                                                                <td>
                                                                    {{
                                                                        index +
                                                                        1
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        item
                                                                            .producto
                                                                            .nombre
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        item.cantidad
                                                                    }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </v-table>
                                                </v-col>
                                            </v-row>
                                        </td>
                                    </tr>
                                </template>
                                <template v-else>
                                    <tr>
                                        <td>
                                            <ul class="flex-content">
                                                <li
                                                    class="flex-item"
                                                    data-label="Id:"
                                                >
                                                    {{ item.id }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Tipo de Salida:"
                                                >
                                                    {{
                                                        item.tipo_salida.nombre
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Unidad Solicitante:"
                                                >
                                                    {{
                                                        item.unidad_solicitante
                                                    }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Descripción:"
                                                >
                                                    {{ item.descripcion }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha de Salida:"
                                                >
                                                    {{ item.fecha_salida_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha de Registro"
                                                >
                                                    {{ item.fecha_registro_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Mas:"
                                                >
                                                    <v-btn
                                                        :icon="
                                                            item.mas
                                                                ? 'mdi-chevron-down'
                                                                : 'mdi-chevron-left'
                                                        "
                                                        @click="
                                                            item.mas = !item.mas
                                                        "
                                                    ></v-btn>
                                                </li>
                                                <template v-if="item.mas">
                                                    <li
                                                        class="flex-item"
                                                        data-label="Salida Productos:"
                                                    >
                                                        <v-table
                                                            class="border tabla_info"
                                                        >
                                                            <tbody>
                                                                <tr
                                                                    v-for="(
                                                                        item,
                                                                        index
                                                                    ) in item.salida_detalles"
                                                                >
                                                                    <td>
                                                                        N°:
                                                                        {{
                                                                            index +
                                                                            1
                                                                        }}
                                                                    </td>
                                                                    <td>
                                                                        Producto:
                                                                        {{
                                                                            item
                                                                                .producto
                                                                                .nombre
                                                                        }}
                                                                    </td>
                                                                    <td>
                                                                        Cantidad:
                                                                        {{
                                                                            item.cantidad
                                                                        }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </v-table>
                                                    </li>
                                                </template>
                                            </ul>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center pa-5"
                                                >
                                                    <v-btn
                                                        color="yellow-accent-2"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="pdf(item)"
                                                        icon="mdi-file-outline"
                                                    ></v-btn>
                                                    <v-btn
                                                        color="primary"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="verSalida(item)"
                                                        icon="mdi-eye-circle"
                                                    ></v-btn>
                                                    <!-- <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'salidas.edit'
                                                            )
                                                        "
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarSalida(item)
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'salidas.destroy'
                                                            )
                                                        "
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarSalida(
                                                                item
                                                            )
                                                        "
                                                        icon="mdi-trash-can"
                                                    ></v-btn> -->
                                                </v-col>
                                            </v-row>
                                        </td>
                                    </tr>
                                </template>
                            </template>
                        </v-data-table-server>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>
