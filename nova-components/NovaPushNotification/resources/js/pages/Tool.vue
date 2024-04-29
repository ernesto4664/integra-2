<template>
  <Head title="Nova Push Notification" />
  <Heading class="mb-6">Nova Push Notification</Heading>
  <Card>
    <div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label class="inline-block text-80 pt-2 leading-tight" for="fecha"
            >Fecha y hora</label
          >
        </div>
        <div class="py-6 px-8 w-1/2">
          <input
            type="datetime-local"
            class="form-control form-input form-input-bordered"
            v-model="datetime"
            for="fecha"
          />
        </div>
      </div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label class="inline-block text-80 pt-2 leading-tight" for="heading"
            >Nombre</label
          >
        </div>
        <div class="py-6 px-8 w-1/2">
          <input
            v-model="heading"
            :id="heading"
            type="text"
            class="w-full form-control form-input form-input-bordered"
            required
          />
        </div>
      </div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label class="inline-block text-80 pt-2 leading-tight" for="heading"
            >Tipo</label
          >
        </div>
        <div class="py-6 px-8 w-1/2">
          <select
            v-model="type"
            id="type"
            class="w-full form-control form-input form-input-bordered"
          >
            <option v-bind:value="1">Liquidación de sueldo</option>
            <option v-bind:value="2">Noticias</option>
            <option v-bind:value="3">Comunicados</option>
            <option v-bind:value="4">Ofertas laborales</option>
          </select>
        </div>
      </div>
      <div v-if="type == 2">
        <div class="flex border-b border-40">
          <div class="w-1/5 py-6 px-8">
            <label class="inline-block text-80 pt-2 leading-tight" for="heading"
              >Noticias</label
            >
          </div>
          <div class="py-6 px-8 w-1/2">
            <select
              v-model="post"
              id="post"
              class="w-full form-control form-input form-input-bordered"
            >
              <option v-for="post in posts" :key="post.id" :value="post.id">
                {{ post.title }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div v-if="type == 4">
        <div class="flex border-b border-40">
          <div class="w-1/5 py-6 px-8">
            <label class="inline-block text-80 pt-2 leading-tight" for="heading"
              >Ofertas laborales</label
            >
          </div>
          <div class="py-6 px-8 w-1/2">
            <select
              v-model="jobOffer"
              id="post"
              class="w-full form-control form-input form-input-bordered"
            >
              <option
                v-for="jobOffer in jobOffers"
                :key="jobOffer.id"
                :value="jobOffer.id"
              >
                {{ jobOffer.title }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div v-if="type == 3">
        <div class="flex border-b border-40">
          <div class="w-1/5 py-6 px-8">
            <label class="inline-block text-80 pt-2 leading-tight" for="heading"
              >Comunicados</label
            >
          </div>
          <div class="py-6 px-8 w-1/2">
            <select
              v-model="release"
              id="release"
              class="w-full form-control form-input form-input-bordered"
            >
              <option
                v-for="release in releases"
                :key="release.id"
                :value="release.id"
              >
                {{ release.title }}
              </option>
            </select>
          </div>
        </div>
      </div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label class="inline-block text-80 pt-2 leading-tight" for="text"
            >Descripción</label
          >
        </div>
        <div class="py-6 px-8 w-1/2">
          <textarea
            v-model="text"
            :id="text"
            class="w-full form-control form-input form-input-bordered py-3 h-auto"
          ></textarea>
        </div>
      </div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label class="inline-block text-80 pt-2 leading-tight" for="heading">Cargo</label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <Multiselect
            mode="tags"
            :close-on-select="false"
            :searchable="true"
            v-model="valuePosition"
            :options="positions"
            placeholder="Seleccione el cargo"
          ></Multiselect>
        </div>
      </div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label class="inline-block text-80 pt-2 leading-tight" for="heading">Dependencia</label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <Multiselect
            mode="tags"
            :close-on-select="false"
            :searchable="true"
            v-model="valueDistrict"
            :options="districts"
            placeholder="Seleccione el cargo"
          ></Multiselect>
        </div>
      </div>
      <div class="flex border-b border-40">
        <div class="w-1/5 py-6 px-8">
          <label class="inline-block text-80 pt-2 leading-tight" for="heading">Region</label>
        </div>
        <div class="py-6 px-8 w-1/2">
          <Multiselect
            mode="tags"
            :close-on-select="false"
            :searchable="true"
            v-model="valueRegion"
            :options="regions"
            placeholder="Seleccione la región"
          ></Multiselect>
        </div>
      </div>
    </div>
    <div class="bg-30 flex px-8 py-4">
      <button
        class="shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900 cursor-pointer rounded text-sm font-bold focus:outline-none focus:ring ring-primary-200 dark:ring-gray-600 inline-flex items-center justify-center h-9 px-3 shadow relative bg-primary-500 hover:bg-primary-400 text-white dark:text-gray-900"
        @click="sendPushNotification">
        Enviar
      </button>
    </div>
  </Card>
</template>
<script>
import Multiselect from "@vueform/multiselect";
import axios from "axios";
export default {
  components: { Multiselect },
  data: () => ({
    heading: "",
    text: "",
    type: "",
    date: "",
    releases: [],
    districts: [],
    posts: [],
    regions: [],
    positions: [],
    valueRegion: [],
    valueDistrict: [],
    valuePosition: [],
    datetime: null,
    options: [
      "Select option",
      "options",
      "selected",
      "mulitple",
      "label",
      "searchable",
      "clearOnSelect",
      "hideSelected",
      "maxHeight",
      "allowEmpty",
      "showLabels",
      "onChange",
      "touched",
    ],
  }),
  methods: {
    sendPushNotification() {
      axios
        .post("/nova-vendor/nova-push-notification/send", {
          heading: this.heading,
          text: this.text,
          districts: this.valueDistrict,
          positions: this.valuePosition,
          regions: this.valueRegion,
          type: this.type,
          post_id: this.post,
          release_id: this.release,
          jobOffer_id: this.jobOffer,
          datetime: this.datetime,
        })
        .then((response) => {
            if (response.status == 200) {
              this.heading = "";
              this.datetime = "";
              this.text = "";
              this.type = "";
              this.valueDistrict = [];
              this.valuePosition = [];
              this.valueRegion = [];
              this.post = "";
              this.release_id = "";
              this.jobOffer_id = "";
              Nova.success(response.data.data);
            }
          },
          (error) => {
            var listError = error.response.data;
            for (const [key, value] of Object.entries(listError)) {
              Nova.error(value);
            }
          }
        );
    },
    getDistricts() {
      Nova.request()
        .get("/nova-vendor/nova-push-notification/districts")
        .then((data) => data.data.data)
        .then((districts) => {
          this.districts = districts;
        });
    },
    getPositions() {
      Nova.request()
        .get("/nova-vendor/nova-push-notification/positions")
        .then((data) => data.data.data)
        .then((positions) => {
          this.positions = positions;
        });
    },
    getRegions() {
      Nova.request()
        .get("/nova-vendor/nova-push-notification/regions")
        .then((data) => data.data.data)
        .then((regions) => {
          this.regions = regions;
        });
    },
    getPost() {
      Nova.request()
        .get("/nova-vendor/nova-push-notification/posts")
        .then((data) => data.data.data)
        .then((posts) => {
          this.posts = posts;
        });
    },
    getReleases() {
      Nova.request()
        .get("/nova-vendor/nova-push-notification/releases")
        .then((data) => data.data.data)
        .then((releases) => {
          this.releases = releases;
        });
    },
    jobOffers() {
      Nova.request()
        .get("/nova-vendor/nova-push-notification/jobOffers")
        .then((data) => data.data.data)
        .then((jobOffers) => {
          this.jobOffers = jobOffers;
        });
    },
  },
  created() {
    this.getDistricts();
    this.getPositions();
    this.getRegions();
    this.getPost();
    this.getReleases();
    this.jobOffers();
  },
};
</script>
<style src="@vueform/multiselect/themes/default.css"></style>