<template>
  <div class="container-fluid">
    <form @submit.prevent="submitForm">
      <div class="row">
        <div class="col-md-12">
          <div class="card">
            <div class="card-header card-header-primary card-header-icon">
              <div class="card-icon">
                <i class="material-icons">edit</i>
              </div>
              <h4 class="card-title">
                {{ $t('global.edit') }}
                <strong>{{ $t('cruds.profile.title_singular') }}</strong>
              </h4>
            </div>
            <div class="card-body">
              <back-button></back-button>
            </div>
            <div class="card-body">
              <bootstrap-alert />
              <div class="row">
                <div class="col-md-12">
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.user_id !== null,
                      'is-focused': activeField == 'user'
                    }"
                  >
                    <label class="bmd-label-floating required">{{
                      $t('cruds.profile.fields.user')
                    }}</label>
                    <v-select
                      name="user"
                      label="email"
                      :key="'user-field'"
                      :value="entry.user_id"
                      :options="lists.user"
                      :reduce="entry => entry.id"
                      @input="updateUser"
                      @search.focus="focusField('user')"
                      @search.blur="clearFocus"
                    />
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.dob,
                      'is-focused': activeField == 'dob'
                    }"
                  >
                    <label class="bmd-label-floating">{{
                      $t('cruds.profile.fields.dob')
                    }}</label>
                    <datetime-picker
                      class="form-control"
                      type="text"
                      picker="date"
                      :value="entry.dob"
                      @input="updateDob"
                      @focus="focusField('dob')"
                      @blur="clearFocus"
                    >
                    </datetime-picker>
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.sex,
                      'is-focused': activeField == 'sex'
                    }"
                  >
                    <label class="bmd-label-floating">{{
                      $t('cruds.profile.fields.sex')
                    }}</label>
                    <v-select
                      name="sex"
                      :key="'sex-field'"
                      :value="entry.sex"
                      :options="lists.sex"
                      :reduce="entry => entry.value"
                      @input="updateSex"
                      @search.focus="focusField('sex')"
                      @search.blur="clearFocus"
                    />
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.religion,
                      'is-focused': activeField == 'religion'
                    }"
                  >
                    <label class="bmd-label-floating">{{
                      $t('cruds.profile.fields.religion')
                    }}</label>
                    <v-select
                      name="religion"
                      :key="'religion-field'"
                      :value="entry.religion"
                      :options="lists.religion"
                      :reduce="entry => entry.value"
                      @input="updateReligion"
                      @search.focus="focusField('religion')"
                      @search.blur="clearFocus"
                    />
                  </div>
                  <div
                    class="form-group bmd-form-group"
                    :class="{
                      'has-items': entry.ethnicity,
                      'is-focused': activeField == 'ethnicity'
                    }"
                  >
                    <label class="bmd-label-floating">{{
                      $t('cruds.profile.fields.ethnicity')
                    }}</label>
                    <v-select
                      name="ethnicity"
                      :key="'ethnicity-field'"
                      :value="entry.ethnicity"
                      :options="lists.ethnicity"
                      :reduce="entry => entry.value"
                      @input="updateEthnicity"
                      @search.focus="focusField('ethnicity')"
                      @search.blur="clearFocus"
                    />
                  </div>
                </div>
              </div>
            </div>
            <div class="card-footer">
              <vue-button-spinner
                class="btn-primary"
                :status="status"
                :isLoading="loading"
                :disabled="loading"
              >
                {{ $t('global.save') }}
              </vue-button-spinner>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  data() {
    return {
      status: '',
      activeField: ''
    }
  },
  computed: {
    ...mapGetters('ProfilesSingle', ['entry', 'loading', 'lists'])
  },
  beforeDestroy() {
    this.resetState()
  },
  watch: {
    '$route.params.id': {
      immediate: true,
      handler() {
        this.resetState()
        this.fetchEditData(this.$route.params.id)
      }
    }
  },
  methods: {
    ...mapActions('ProfilesSingle', [
      'fetchEditData',
      'updateData',
      'resetState',
      'setUser',
      'setDob',
      'setSex',
      'setReligion',
      'setEthnicity'
    ]),
    updateUser(value) {
      this.setUser(value)
    },
    updateDob(e) {
      this.setDob(e.target.value)
    },
    updateSex(value) {
      this.setSex(value)
    },
    updateReligion(value) {
      this.setReligion(value)
    },
    updateEthnicity(value) {
      this.setEthnicity(value)
    },
    submitForm() {
      this.updateData()
        .then(() => {
          this.$router.push({ name: 'profiles.index' })
          this.$eventHub.$emit('update-success')
        })
        .catch(error => {
          this.status = 'failed'
          _.delay(() => {
            this.status = ''
          }, 3000)
        })
    },
    focusField(name) {
      this.activeField = name
    },
    clearFocus() {
      this.activeField = ''
    }
  }
}
</script>
