<script>
const breadbrums = [
    {
        title: "Inicio",
        disabled: false,
        url: route("inicio"),
        name_url: "inicio",
    },
    {
        title: "Institución",
        disabled: false,
        url: route("configuracions.index"),
        name_url: "configuracions.index",
    },
];
</script>
<script setup>
// componentes
import BreadBrums from "@/Components/BreadBrums.vue";
import { ref, onMounted, reactive } from "vue";
import { useForm, Head } from "@inertiajs/vue3";
import { useConfiguracion } from "@/composables/configuracion/useConfiguracion";
import { useApp } from "@/composables/useApp";
const { setLoading } = useApp();
onMounted(() => {
    setTimeout(() => {
        setLoading(false);
    }, 300);
});
const props = defineProps({
    configuracion: {
        type: Object,
    },
});

const configuracion = ref(props.configuracion);
const window = ref(0);

const cambiaVentana = (val) => {
    window.value = val;
};

configuracion.value["_method"] = "put";

let form = useForm(configuracion.value);

const enviaFormulario = () => {
    form.post(route("configuracions.update", configuracion.value.id), {
        onSuccess: () => {
            setTimeout(() => {
                obtnerConfiguracion();
                cambiaVentana(0);
            }, 300);
        },
        onError: (err) => {},
    });
};

const { getConfiguracion } = useConfiguracion();

const obtnerConfiguracion = async () => {
    try {
        configuracion.value = await getConfiguracion(configuracion.id);
        configuracion.value["_method"] = "put";
        form = useForm(configuracion.value);
        limpiaRefs();
    } catch (error) {
        console.error(error);
    }
};

function cargaArchivo(e, key) {
    form[key] = null;
    form[key] = e.target.files[0];
}

const logo = ref(null);
function limpiaRefs() {
    logo.value.reset();
}
</script>
<template>
    <Head title="Institución"></Head>
    <v-container>
        <BreadBrums :breadbrums="breadbrums"></BreadBrums>
        <v-row class="mt-0">
            <v-col cols="12" class="d-flex justify-end">
                <v-btn
                    prepend-icon="mdi-pencil"
                    color="primary"
                    v-if="window == 0"
                    @click="cambiaVentana(1)"
                >
                    Editar</v-btn
                >
                <v-btn
                    prepend-icon="mdi-close"
                    class="mr-2"
                    v-if="window == 1"
                    @click="
                        cambiaVentana(0);
                        obtnerConfiguracion();
                    "
                >
                    Cancelar</v-btn
                >
                <v-btn
                    prepend-icon="mdi-content-save"
                    color="primary"
                    v-if="window == 1"
                    @click="enviaFormulario"
                >
                    Guardar</v-btn
                >
            </v-col>
        </v-row>
        <v-row class="mt-0">
            <v-col cols="12">
                <v-window v-model="window">
                    <v-window-item :key="0">
                        <v-row>
                            <v-col cols="12" sm="12">
                                <v-card>
                                    <v-card-text>
                                        <v-row>
                                            <v-col
                                                cols="12"
                                                sm="4"
                                                md="2"
                                                class="text-center"
                                            >
                                                <v-img
                                                    cover
                                                    v-if="configuracion.url_logo"
                                                    :src="configuracion.url_logo"
                                                    class="w-75 mx-auto"
                                                ></v-img>
                                                <v-avatar
                                                    v-else
                                                    color="grey"
                                                    size="100"
                                                >
                                                    <span
                                                        class="text-h5"
                                                        v-text="
                                                            configuracion.iniciales_nombre
                                                        "
                                                    ></span>
                                                </v-avatar>
                                                <span
                                                    class="text-h6 d-block mt-3"
                                                    >{{
                                                        configuracion.razon_social
                                                    }}</span
                                                >

                                                {{ configuracion.ciudad }}<br />
                                                {{ configuracion.dir }}
                                            </v-col>
                                            <v-divider vertical></v-divider>
                                            <v-col
                                                cols="10"
                                                sm="8"
                                                md="10"
                                                class="d-flex align-center"
                                            >
                                                <v-row>
                                                    <v-col cols="12">
                                                        <h4
                                                            class="text-center text-h6"
                                                        >
                                                            INFORMACIÓN
                                                        </h4>
                                                    </v-col>
                                                    <v-col
                                                        cols="6"
                                                        md="3"
                                                        xl="3"
                                                    >
                                                        <div
                                                            class="text-caption font-weight-bold"
                                                        >
                                                            Nombre Sistema
                                                        </div>
                                                        {{
                                                            configuracion.nombre_sistema
                                                        }}
                                                    </v-col>
                                                    <v-col
                                                        cols="6"
                                                        md="3"
                                                        xl="3"
                                                    >
                                                        <div
                                                            class="text-caption font-weight-bold"
                                                        >
                                                            Alias
                                                        </div>
                                                        {{ configuracion.alias }}
                                                    </v-col>
                                                    <v-col
                                                        cols="6"
                                                        md="3"
                                                        xl="3"
                                                    >
                                                        <div
                                                            class="text-caption font-weight-bold"
                                                        >
                                                            Teléfono
                                                        </div>
                                                        {{ configuracion.fono }}
                                                    </v-col>
                                                    <v-col
                                                        cols="6"
                                                        md="3"
                                                        xl="3"
                                                    >
                                                        <div
                                                            class="text-caption font-weight-bold"
                                                        >
                                                            Correo
                                                        </div>
                                                        {{ configuracion.correo }}
                                                    </v-col>
                                                    <v-col
                                                        cols="6"
                                                        md="3"
                                                        xl="3"
                                                    >
                                                        <div
                                                            class="text-caption font-weight-bold"
                                                        >
                                                            Web
                                                        </div>
                                                        {{ configuracion.web }}
                                                    </v-col>
                                                    <v-col
                                                        cols="6"
                                                        md="3"
                                                        xl="3"
                                                    >
                                                        <div
                                                            class="text-caption font-weight-bold"
                                                        >
                                                            Actividad
                                                        </div>
                                                        {{
                                                            configuracion.actividad
                                                        }}
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-window-item>
                    <v-window-item :key="1">
                        <v-row>
                            <v-col cols="12">
                                <v-card>
                                    <v-card-title
                                        >Modificar información</v-card-title
                                    >
                                    <v-card-text>
                                        <v-container>
                                            <form @submit="enviaFormulario">
                                                <v-row>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors
                                                                    ?.razon_social
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors
                                                                    ?.razon_social
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors
                                                                    ?.razon_social
                                                                    ? form
                                                                          .errors
                                                                          ?.razon_social
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Razón Social*"
                                                            v-model="
                                                                form.razon_social
                                                            "
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors
                                                                    ?.nombre_sistema
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors
                                                                    ?.nombre_sistema
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors
                                                                    ?.nombre_sistema
                                                                    ? form
                                                                          .errors
                                                                          ?.nombre_sistema
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Nombre Sistema*"
                                                            v-model="
                                                                form.nombre_sistema
                                                            "
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors
                                                                    ?.alias
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors
                                                                    ?.alias
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors
                                                                    ?.alias
                                                                    ? form
                                                                          .errors
                                                                          ?.alias
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Alias"
                                                            v-model="form.alias"
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors
                                                                    ?.ciudad
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors
                                                                    ?.ciudad
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors
                                                                    ?.ciudad
                                                                    ? form
                                                                          .errors
                                                                          ?.ciudad
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Ciudad"
                                                            v-model="
                                                                form.ciudad
                                                            "
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors?.dir
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors?.dir
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors?.dir
                                                                    ? form
                                                                          .errors
                                                                          ?.dir
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Dirección"
                                                            v-model="form.dir"
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors
                                                                    ?.fono
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors
                                                                    ?.fono
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors
                                                                    ?.fono
                                                                    ? form
                                                                          .errors
                                                                          ?.fono
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Teléfono"
                                                            v-model="form.fono"
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors
                                                                    ?.correo
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors
                                                                    ?.correo
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors
                                                                    ?.correo
                                                                    ? form
                                                                          .errors
                                                                          ?.correo
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Correo"
                                                            v-model="
                                                                form.correo
                                                            "
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors?.web
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors?.web
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors?.web
                                                                    ? form
                                                                          .errors
                                                                          ?.web
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Sitio Web"
                                                            v-model="form.web"
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-text-field
                                                            :hide-details="
                                                                form.errors
                                                                    ?.actividad
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors
                                                                    ?.actividad
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors
                                                                    ?.actividad
                                                                    ? form
                                                                          .errors
                                                                          ?.actividad
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            color="grey"
                                                            label="Actividad*"
                                                            v-model="
                                                                form.actividad
                                                            "
                                                            required
                                                        ></v-text-field>
                                                    </v-col>
                                                    <v-col
                                                        cols="12"
                                                        sm="6"
                                                        md="6"
                                                        xl="4"
                                                    >
                                                        <v-file-input
                                                            :hide-details="
                                                                form.errors
                                                                    ?.logo
                                                                    ? false
                                                                    : true
                                                            "
                                                            :error="
                                                                form.errors
                                                                    ?.logo
                                                                    ? true
                                                                    : false
                                                            "
                                                            :error-messages="
                                                                form.errors
                                                                    ?.logo
                                                                    ? form
                                                                          .errors
                                                                          ?.logo
                                                                    : ''
                                                            "
                                                            density="compact"
                                                            variant="outlined"
                                                            accept="image/png, image/jpeg, image/bmp, image/webp"
                                                            placeholder="Logo"
                                                            prepend-icon="mdi-camera"
                                                            label="Logo"
                                                            @change="
                                                                cargaArchivo(
                                                                    $event,
                                                                    'logo'
                                                                )
                                                            "
                                                            ref="logo"
                                                        ></v-file-input>
                                                    </v-col>
                                                </v-row>
                                            </form>
                                        </v-container>
                                    </v-card-text>
                                </v-card>
                            </v-col>
                        </v-row>
                    </v-window-item>
                </v-window>
            </v-col>
        </v-row>
    </v-container>
</template>
