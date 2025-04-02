<tr class="align-middle text-center" x-data="{
        active: @entangle('state.active'),
        open_time: @entangle('state.open_time'),
        close_time: @entangle('state.close_time'),
        error: '',
        validate() {
            if (this.active) {
                if (!this.open_time) {
                    this.error = 'error';
                    return false;
                }
                if (!this.close_time) {
                    this.error = 'error';
                    return false;
                }
                if (this.close_time <= this.open_time) {
                    this.error = 'error';
                    return false;
                }
            }
            this.error = '';
            return true;
        },
        init() {
            this.$watch('active', value => {
                $wire.set('state.active', value);
                this.validate();
            });
            this.$watch('open_time', value => {
                $wire.set('state.open_time', value);
                this.validate();
            });
            this.$watch('close_time', value => {
                $wire.set('state.close_time', value);
                this.validate();
            });
        }
    }">
    <td class="fw-semibold">{{ $weekdayName }}</td>
    <td>
        <div class="form-check form-switch d-inline-block">
            <input type="checkbox"
                   x-model="active"
                   class="form-check-input"
                   id="day_{{ $day }}">
        </div>
    </td>
    <td>
        <!-- Apply red border if error exists -->
        <div class="position-relative d-inline-block">
            <input type="time"
                   :disabled="!active"
                   x-model="open_time"
                   :class="{'border border-danger': error !== ''}"
                   class="form-control w-auto"/>
        </div>
    </td>
    <td>
        <div class="position-relative d-inline-block">
            <input type="time"
                   :disabled="!active"
                   x-model="close_time"
                   :class="{'border border-danger': error !== ''}"
                   class="form-control w-auto"/>
        </div>
    </td>
</tr>
