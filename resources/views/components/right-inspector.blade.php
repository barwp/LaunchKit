<div class="builder-panel builder-panel-sticky">
    <div class="builder-panel-header">
        <div>
            <p class="builder-eyebrow">Inspector</p>
            <h3 class="builder-panel-title">Selected Element</h3>
        </div>
    </div>

    <template x-if="selectedMeta === 'hero'">
        <div class="builder-stack">
            <label class="builder-field"><span>Badge</span><input x-model="hero.badge"></label>
            <label class="builder-field"><span>Headline</span><textarea x-model="hero.headline"></textarea></label>
            <label class="builder-field"><span>Subheadline</span><textarea x-model="hero.subheadline"></textarea></label>
            <label class="builder-field"><span>CTA Text</span><input x-model="hero.cta_text"></label>
            <label class="builder-field"><span>CTA Link</span><input x-model="hero.cta_link"></label>
            <div class="builder-divider"></div>
            <h4 class="builder-side-title">Hero Image</h4>
            <div class="builder-action-row">
                <label class="builder-icon-btn cursor-pointer">
                    <span>Upload / Replace</span>
                    <input type="file" class="hidden" accept="image/*" @change="openCropperFromInput($event, { target: 'hero', field: 'visual_image' })">
                </label>
                <button type="button" class="builder-danger-btn" @click="hero.visual_image = ''">Delete Image</button>
            </div>
            <img x-show="hero.visual_image" :src="hero.visual_image" alt="" class="builder-inline-image">
        </div>
    </template>

    <template x-if="currentSection()">
        <div class="builder-stack">
            <div class="builder-action-row">
                <button type="button" class="builder-icon-btn" @click="moveSection(selectedIndex, -1)">Naik</button>
                <button type="button" class="builder-icon-btn" @click="moveSection(selectedIndex, 1)">Turun</button>
                <button type="button" class="builder-icon-btn" @click="duplicateSection(selectedIndex)">Duplicate</button>
                <button type="button" class="builder-icon-btn" @click="toggleSection(selectedIndex)">Hide</button>
                <button type="button" class="builder-danger-btn" @click="removeSection(selectedIndex)">Delete</button>
            </div>

            <template x-for="field in editableFields()" :key="field">
                <label class="builder-field">
                    <span x-text="labelize(field)"></span>
                    <template x-if="fieldIsLong(field)">
                        <textarea :value="currentValue(field)" @input="setCurrentField(field, $event.target.value)"></textarea>
                    </template>
                    <template x-if="!fieldIsLong(field)">
                        <input :value="currentValue(field)" @input="setCurrentField(field, $event.target.value)">
                    </template>
                </label>
            </template>

            <div class="builder-divider"></div>
            <h4 class="builder-side-title">Spacing</h4>
            @foreach (['padding_top' => 'Padding Top', 'padding_bottom' => 'Padding Bottom', 'padding_left' => 'Padding Left', 'padding_right' => 'Padding Right', 'margin_top' => 'Margin Top', 'margin_bottom' => 'Margin Bottom', 'section_gap' => 'Section Gap', 'max_width' => 'Max Width'] as $key => $label)
                <label class="builder-field">
                    <span>{{ $label }}</span>
                    <select :value="currentSection().style.{{ $key }} ?? ''" @change="setSectionStyle('{{ $key }}', $event.target.value)">
                        @foreach ($spacingScale as $scale)
                            <option value="{{ $scale }}">{{ $scale }}</option>
                        @endforeach
                        @if ($key === 'max_width')
                            @foreach ([960, 1080, 1180, 1280, 1440] as $w)
                                <option value="{{ $w }}">{{ $w }}</option>
                            @endforeach
                        @endif
                    </select>
                </label>
            @endforeach
            <label class="builder-field">
                <span>Container Mode</span>
                <select :value="currentSection().style.container_mode ?? 'boxed'" @change="setSectionStyle('container_mode', $event.target.value)">
                    <option value="full">Full Width</option>
                    <option value="boxed">Boxed</option>
                    <option value="narrow">Narrow</option>
                    <option value="custom">Custom</option>
                </select>
            </label>
            <label class="builder-field">
                <span>Text Align</span>
                <select :value="currentSection().style.text_align ?? 'left'" @change="setSectionStyle('text_align', $event.target.value)">
                    <option value="left">Left</option>
                    <option value="center">Center</option>
                    <option value="right">Right</option>
                </select>
            </label>

            <template x-if="currentSection()?.type === 'image'">
                <div class="builder-stack">
                    <div class="builder-divider"></div>
                    <h4 class="builder-side-title">Gallery</h4>
                    <label class="builder-field">
                        <span>Layout</span>
                        <select x-model="currentSection().content.layout">
                            <option value="grid-2">Grid 2</option>
                            <option value="grid-3">Grid 3</option>
                            <option value="masonry-simple">Masonry Simple</option>
                            <option value="slider-style-preview">Slider Style Preview</option>
                            <option value="collage-clean">Collage Clean</option>
                        </select>
                    </label>
                    <label class="builder-icon-btn cursor-pointer">
                        <span>Upload New Image</span>
                        <input type="file" class="hidden" accept="image/*" @change="addImageSectionItem($event)">
                    </label>
                </div>
            </template>

            <div x-show="Array.isArray(currentSection()?.content?.items)">
                <div class="builder-divider"></div>
                <div class="flex items-center justify-between gap-3">
                    <h4 class="builder-side-title">Items</h4>
                    <button type="button" class="builder-icon-btn" @click="addItem()">Add Item</button>
                </div>
                <div class="builder-stack mt-3">
                    <template x-for="(item, itemIndex) in currentSection().content.items" :key="item.id || itemIndex">
                        <div class="builder-repeater-card">
                            <template x-if="typeof item === 'string'">
                                <div class="builder-stack">
                                    <textarea class="builder-code-inline" :value="item" @input="setStringItem(itemIndex, $event.target.value)"></textarea>
                                    <div class="builder-action-row">
                                        <button type="button" class="builder-icon-btn" @click="moveItem(itemIndex, -1)">Up</button>
                                        <button type="button" class="builder-icon-btn" @click="moveItem(itemIndex, 1)">Down</button>
                                        <button type="button" class="builder-danger-btn" @click="removeItem(itemIndex)">Delete</button>
                                    </div>
                                </div>
                            </template>

                            <template x-if="typeof item === 'object'">
                                <div class="builder-stack">
                                    <template x-for="(fieldValue, fieldKey) in item" :key="fieldKey">
                                        <div class="builder-field">
                                            <span x-text="labelize(fieldKey)"></span>
                                            <template x-if="fieldKey === 'image' || fieldKey === 'image_url'">
                                                <div class="builder-stack">
                                                    <img x-show="fieldValue" :src="fieldValue" alt="" class="builder-inline-image">
                                                    <div class="builder-action-row">
                                                        <label class="builder-icon-btn cursor-pointer">
                                                            <span>Upload</span>
                                                            <input type="file" class="hidden" accept="image/*" @change="openCropperFromInput($event, { target: 'item', itemIndex, field: fieldKey })">
                                                        </label>
                                                        <button type="button" class="builder-icon-btn" @click="setObjectItem(itemIndex, fieldKey, '')">Replace URL</button>
                                                        <button type="button" class="builder-danger-btn" @click="setObjectItem(itemIndex, fieldKey, '')">Delete</button>
                                                    </div>
                                                    <input :value="fieldValue" @input="setObjectItem(itemIndex, fieldKey, $event.target.value)">
                                                </div>
                                            </template>
                                            <template x-if="fieldKey !== 'image' && fieldKey !== 'image_url'">
                                                <textarea :value="fieldValue" @input="setObjectItem(itemIndex, fieldKey, $event.target.value)"></textarea>
                                            </template>
                                        </div>
                                    </template>
                                    <div class="builder-action-row">
                                        <button type="button" class="builder-icon-btn" @click="moveItem(itemIndex, -1)">Up</button>
                                        <button type="button" class="builder-icon-btn" @click="moveItem(itemIndex, 1)">Down</button>
                                        <button type="button" class="builder-danger-btn" @click="removeItem(itemIndex)">Delete</button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </template>
</div>
