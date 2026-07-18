<!-- Large Chart Area -->
<div
    x-data="{ period: '7', trends: { '7': {{ json_encode($trends['days_7']) }}, '30': {{ json_encode($trends['days_30']) }} } }"
    class="col-span-12 xl:col-span-8 bg-surface-container-lowest p-8 rounded-xl shadow-sm">
    <div class="flex justify-between items-start mb-8">
        <div>
            <h2
                class="text-xl font-headline font-bold text-on-surface">
                Average FGB Trends</h2>
            <p class="text-sm text-on-surface-variant">
                Aggregate fasting blood glucose levels over <span x-text="period === '7' ? 'the last 7 days' : 'the last 30 days'">the last 7 days</span></p>
        </div>
        <select
            x-model="period"
            class="bg-surface-container-low border-none rounded-lg text-sm font-medium py-2 pl-4 pr-10 outline-none focus:ring-2 focus:ring-primary/20">
            <option value="7">Last 7 Days</option>
            <option value="30">Last 30 Days</option>
        </select>
    </div>
    
    <!-- Visualization: Dynamic Bar Chart UI -->
    <div 
        :class="period === '7' ? 'gap-2' : 'gap-1'"
        class="h-64 flex items-stretch justify-between px-4 relative mt-6 transition-all duration-300">
        <!-- Y-axis markers -->
        <div
            class="absolute left-0 top-0 bottom-0 flex flex-col justify-between text-[9px] font-bold text-on-surface-variant/40 pt-2 pb-8">
            <span>250 mg/dL</span>
            <span>180 mg/dL</span>
            <span>120 mg/dL</span>
            <span>70 mg/dL</span>
        </div>
        
        <div class="w-10"></div> <!-- Spacer for Y-axis -->
        
        <template x-for="(item, index) in trends[period]" :key="item.label">
            <div class="flex flex-col items-center flex-1 group h-full relative justify-end">
                <div
                    class="w-full bg-surface-container-low rounded-t-lg relative flex items-end justify-center h-44 transition-all hover:bg-primary/10 mb-6">
                    <div
                        :class="item.is_current ? 'bg-primary' : 'bg-blue-400/30 group-hover:bg-primary'"
                        class="w-3/4 rounded-t-md transition-all"
                        :style="'height: ' + Math.min(Math.max((item.value / 250) * 100, 10), 100) + '%'">
                    </div>
                    <div
                        class="absolute -top-8 bg-on-surface text-white text-[9px] px-1.5 py-0.5 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-150 pointer-events-none whitespace-nowrap z-20 shadow-sm"
                        x-text="Math.round(item.value) + ' mg/dL'"></div>
                </div>
                <span
                    x-text="item.label"
                    :class="(period === '7' || index % 5 === 0 || index === trends[period].length - 1) ? '' : 'hidden'"
                    class="absolute bottom-0 left-0 right-0 text-center text-[9px] font-bold text-on-surface-variant tracking-wider uppercase whitespace-nowrap"></span>
            </div>
        </template>
    </div>
</div>
