<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Egresos Económicos",
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
import { useEgresos } from "@/composables/egresos/useEgresos";
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

const { getEgresosApi, deleteEgreso } = useEgresos();
const responseEgresos = ref([]);
const listEgresos = ref([]);
const itemsPerPage = ref(5);
const headers = ref([
    {
        title: "Id",
        align: "start",
        sortable: false,
    },
    {
        title: "Fecha",
        align: "start",
        sortable: false,
    },
    { title: "Categoría", align: "start", sortable: false },
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
        responseEgresos.value = await getEgresosApi(options.value);
        listEgresos.value = responseEgresos.value.data;
        totalItems.value = parseInt(responseEgresos.value.total);
        loading.value = false;
    }, 300);
};
const recargaEgresos = async () => {
    loading.value = true;
    listEgresos.value = [];
    options.value.search = search.value;
    responseEgresos.value = await getEgresosApi(options.value);
    listEgresos.value = responseEgresos.value.data;
    totalItems.value = parseInt(responseEgresos.value.total);
    setTimeout(() => {
        loading.value = false;
    }, 300);
};

const editarEgreso = (item) => {
    cambiarUrl(route("egresos.edit", item.id));
};

const verEgreso = (item) => {
    cambiarUrl(route("egresos.show", item.id));
};

const pdf = (item) => {
    window.open(route("egresos.pdf", item.id), "_blank");
};

const eliminarEgreso = (item) => {
    Swal.fire({
        title: "¿Quierés eliminar este registro?",
        html: `<strong>${item.fecha_t} - ${item.categoria.nombre}</strong>`,
        showCancelButton: true,
        confirmButtonColor: "#B61431",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "No, cancelar",
        denyButtonText: `No, cancelar`,
    }).then(async (result) => {
        /* Read more about isConfirmed, isDenied below */
        if (result.isConfirmed) {
            let respuesta = await deleteEgreso(item.id);
            if (respuesta && respuesta.sw) {
                recargaEgresos();
            }
        }
    });
};
const verUbicación = async (item) => {};
</script>
<template>
    <Head title="Egresos Económicos"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    v-if="props.auth.user.permisos.includes('egresos.create')"
                    color="primary"
                    prepend-icon="mdi-plus"
                    @click="cambiarUrl(route('egresos.create'))"
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
                            <v-col cols="12" sm="6" md="4">
                                Egresos Económicos</v-col
                            >
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
                            :items="listEgresos"
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
                                        <td>
                                            {{ item.fecha_t }}
                                        </td>
                                        <td>{{ item.categoria.nombre }}</td>
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
                                            <!-- <v-btn
                                                color="yellow-accent-2"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="pdf(item)"
                                                icon="mdi-file-outline"
                                            ></v-btn> -->
                                            <v-btn
                                                color="primary"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="verEgreso(item)"
                                                icon="mdi-eye-circle"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'egresos.edit'
                                                    )
                                                "
                                                color="yellow"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="editarEgreso(item)"
                                                icon="mdi-pencil"
                                            ></v-btn>
                                            <v-btn
                                                v-if="
                                                    props.auth.user.permisos.includes(
                                                        'egresos.destroy'
                                                    )
                                                "
                                                color="error"
                                                size="small"
                                                class="pa-1 ma-1"
                                                @click="eliminarEgreso(item)"
                                                icon="mdi-trash-can"
                                            ></v-btn>
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
                                                                    Concepto
                                                                </th>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    Descripción
                                                                </th>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    Cantidad
                                                                </th>
                                                                <th
                                                                    class="text-center"
                                                                >
                                                                    Monto
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr
                                                                v-for="(
                                                                    item_detalle, index
                                                                ) in item.egreso_detalles"
                                                            >
                                                                <td>
                                                                    {{
                                                                        index +
                                                                        1
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        item_detalle
                                                                            .concepto
                                                                            ?.nombre
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        item_detalle.descripcion
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        item_detalle.cantidad
                                                                    }}
                                                                </td>
                                                                <td>
                                                                    {{
                                                                        item_detalle.monto
                                                                    }}
                                                                </td>
                                                            </tr>
                                                        </tbody>

                                                        <tfoot>
                                                            <tr>
                                                                <th
                                                                    colspan="3"
                                                                    class="text-right font-weight-bold"
                                                                >
                                                                    TOTAL
                                                                </th>
                                                                <th
                                                                    class="text-center font-weight-bold"
                                                                >
                                                                    {{
                                                                        item.total_c
                                                                    }}
                                                                </th>
                                                                <th
                                                                    class="text-center font-weight-bold"
                                                                >
                                                                    {{
                                                                        item.total_m
                                                                    }}
                                                                </th>
                                                                <th></th>
                                                            </tr>
                                                        </tfoot>
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
                                                    data-label="Fecha:"
                                                >
                                                    {{ item.fecha_t }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Categoría:"
                                                >
                                                    {{ item.categoria.nombre }}
                                                </li>
                                                <li
                                                    class="flex-item"
                                                    data-label="Fecha de Egreso:"
                                                >
                                                    {{ item.fecha_egreso_t }}
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
                                                        data-label="Egreso Detalle:"
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
                                                                        Concepto
                                                                    </th>
                                                                    <th
                                                                        class="text-center"
                                                                    >
                                                                        Descripción
                                                                    </th>
                                                                    <th
                                                                        class="text-center"
                                                                    >
                                                                        Cantidad
                                                                    </th>
                                                                    <th
                                                                        class="text-center"
                                                                    >
                                                                        Monto
                                                                    </th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <tr
                                                                    v-for="(
                                                                        item_detalle,
                                                                        index
                                                                    ) in item.egreso_detalles"
                                                                >
                                                                    <td>
                                                                        {{
                                                                            index +
                                                                            1
                                                                        }}
                                                                    </td>
                                                                    <td>
                                                                        {{
                                                                            item_detalle
                                                                                .concepto
                                                                                ?.nombre
                                                                        }}
                                                                    </td>
                                                                    <td>
                                                                        {{
                                                                            item_detalle.descripcion
                                                                        }}
                                                                    </td>
                                                                    <td>
                                                                        {{
                                                                            item_detalle.cantidad
                                                                        }}
                                                                    </td>
                                                                    <td>
                                                                        {{
                                                                            item_detalle.monto
                                                                        }}
                                                                    </td>
                                                                </tr>
                                                            </tbody>

                                                            <tfoot>
                                                                <tr>
                                                                    <th
                                                                        colspan="3"
                                                                        class="text-right font-weight-bold"
                                                                    >
                                                                        TOTAL
                                                                    </th>
                                                                    <th
                                                                        class="text-center font-weight-bold"
                                                                    >
                                                                        {{
                                                                            item.total_c
                                                                        }}
                                                                    </th>
                                                                    <th
                                                                        class="text-center font-weight-bold"
                                                                    >
                                                                        {{
                                                                            item.total_m
                                                                        }}
                                                                    </th>
                                                                    <th></th>
                                                                </tr>
                                                            </tfoot>
                                                        </v-table>
                                                    </li>
                                                </template>
                                            </ul>
                                            <v-row>
                                                <v-col
                                                    cols="12"
                                                    class="text-center pa-5"
                                                >
                                                    <!-- <v-btn
                                                        color="yellow-accent-2"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="pdf(item)"
                                                        icon="mdi-file-outline"
                                                    ></v-btn> -->
                                                    <v-btn
                                                        color="primary"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            verEgreso(item)
                                                        "
                                                        icon="mdi-eye-circle"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'egresos.edit'
                                                            )
                                                        "
                                                        color="yellow"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            editarEgreso(item)
                                                        "
                                                        icon="mdi-pencil"
                                                    ></v-btn>
                                                    <v-btn
                                                        v-if="
                                                            props.auth.user.permisos.includes(
                                                                'egresos.destroy'
                                                            )
                                                        "
                                                        color="error"
                                                        size="small"
                                                        class="pa-1 ma-1"
                                                        @click="
                                                            eliminarEgreso(
                                                                item
                                                            )
                                                        "
                                                        icon="mdi-trash-can"
                                                    ></v-btn>
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
