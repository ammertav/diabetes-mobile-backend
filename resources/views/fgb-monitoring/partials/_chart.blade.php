<div
    class="bg-surface-container-lowest rounded-xl shadow-sm mb-8 overflow-hidden">
    <div class="p-8 border-b border-surface-container">
        <div
            class="flex flex-col lg:flex-row lg:items-center justify-between gap-6">
            <h2 class="text-xl font-bold tracking-tight">FGB Trend Analysis</h2>
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex bg-surface-container-low p-1 rounded-lg">
                    <button
                        @click="chartPeriod = 'daily'; loadChartData()"
                        :class="chartPeriod === 'daily' ? 'bg-white shadow-sm text-on-surface' : 'text-on-surface-variant'"
                        class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">Daily</button>
                    <button
                        @click="chartPeriod = 'weekly'; loadChartData()"
                        :class="chartPeriod === 'weekly' ? 'bg-white shadow-sm text-on-surface' : 'text-on-surface-variant'"
                        class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">Weekly</button>
                    <button
                        @click="chartPeriod = 'monthly'; loadChartData()"
                        :class="chartPeriod === 'monthly' ? 'bg-white shadow-sm text-on-surface' : 'text-on-surface-variant'"
                        class="px-4 py-1.5 text-xs font-bold rounded-md transition-all">Monthly</button>
                </div>
                <select
                    x-model="chartGroup"
                    @change="loadChartData()"
                    class="bg-surface-container-high border-none rounded-xl text-sm font-semibold px-4 py-2 pr-10 focus:ring-primary/20 text-on-surface-variant">
                    <option value="all">Group: All Patients</option>
                    <option value="t2dm">Group: Type 2 Diabetes (T2DM)</option>
                    <option value="prediabetes">Group: Prediabetes</option>
                    <option value="healthy">Group: Healthy</option>
                </select>
            </div>
        </div>
    </div>
    <div class="p-8">
        <!-- Placeholder for Chart -->
        <div
            class="h-72 w-full relative flex items-end justify-between gap-2 px-4">
            <!-- Y-axis markers -->
            <div
                class="absolute left-0 top-0 bottom-0 flex flex-col justify-between text-[10px] font-bold text-on-surface-variant/40 pt-2 pb-8">
                <span>250 mg/dL</span>
                <span>180 mg/dL</span>
                <span>120 mg/dL</span>
                <span>70 mg/dL</span>
            </div>
            <!-- Bars (Simulated Trend) -->
            <div class="w-12"></div> <!-- Spacer for Y-axis -->
            <template x-for="item in chartData" :key="item.label">
                <div
                    @mouseenter="hoveredChartLabel = item.label"
                    @mouseleave="hoveredChartLabel = null"
                    @click="openChartDetail(item.index, item.label)"
                    :class="(hoveredChartLabel === null ? item.is_current : item.label === hoveredChartLabel) ? 'bg-primary shadow-md scale-[1.02]' : 'bg-primary/20 hover:bg-primary/40 scale-100'"
                    class="flex-1 rounded-t-lg relative cursor-pointer transition-all duration-200"
                    :style="'height: ' + Math.min(Math.max((item.value / 250) * 100, 10), 100) + '%'">
                    <div
                        :class="(hoveredChartLabel === null ? item.is_current : item.label === hoveredChartLabel) ? 'opacity-100 scale-100' : 'opacity-0 scale-95 pointer-events-none'"
                        class="absolute -top-10 left-1/2 -translate-x-1/2 bg-on-surface text-white text-[10px] px-2 py-1 rounded transition-all duration-200 whitespace-nowrap"
                        x-text="Math.round(item.value)"></div>
                </div>
            </template>
        </div>
        <!-- X-axis -->
        <div
            class="flex justify-between mt-4 pl-14 pr-2 text-[10px] font-bold text-on-surface-variant uppercase tracking-widest">
            <template x-for="item in chartData" :key="item.label">
                <span
                    :class="(hoveredChartLabel === null ? item.is_current : item.label === hoveredChartLabel) ? 'text-primary font-black scale-105' : 'text-on-surface-variant/70'"
                    class="flex-1 text-center transition-all duration-200"
                    x-text="item.label"></span>
            </template>
        </div>
    </div>
</div>
