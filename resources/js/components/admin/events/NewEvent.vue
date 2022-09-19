<template>
  <div>
    <media-form
      :posttype="'event'"
      :redirectedit="'EditEvent'"
      :newurl="'/d/admin/media/save'"
      :headertitle="'Events'"
    ></media-form>
  </div>
</template>

<script>
import mediaForm from "../medias/MediaForm.vue";
export default {
  components: { mediaForm },
  data() {
    return {
      //   pagetitle: 'Create New' ,
    };
  },
};
</script>

<style>
</style>
