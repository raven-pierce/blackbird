<div class="grid grid-cols-4">
    <div class="mt-24 col-span-2 flex flex-col gap-y-4 self-end">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-200">Report #{{ $this->record->id }}</h1>

        <div class="flex flex-col text-sm text-gray-500 dark:text-gray-400">
            <span><span class="font-medium">From: </span>{{ $this->record->start_date->format('l, d F Y') }}</span>
            <span><span class="font-medium">To: </span>{{ $this->record->end_date->format('l, d F Y') }}</span>

            <span class="mt-4"><span class="font-medium">Generated: </span>{{ $this->record->updated_at->format('l, d F Y') }}</span>
        </div>
    </div>

    <div class="mt-24 col-span-2 flex flex-col text-sm text-gray-500 dark:text-gray-400 text-right self-end">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Student</h2>

        <span class="mt-4 font-medium">{{ $this->record->enrollment->student->name }}</span>
        <span class="mt-2">{{ $this->record->enrollment->student->email }}</span>
    </div>

    <div class="mt-16 col-span-4 flex flex-col text-sm text-gray-500 dark:text-gray-400">
        <h3 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Enrollment</h3>

        <span class="mt-4 font-medium">{{ $this->record->enrollment->section->course->name }}</span>
        <span class="mt-2">{{ $this->record->enrollment->section->course->tutor->name }}, {{ $this->record->enrollment->section->code }}</span>
    </div>

    <div class="mt-16 col-span-4 flex flex-col">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Attendance</h2>

        <div class="mt-4 overflow-hidden rounded">
            <table class="w-full text-left text-sm text-gray-500">
                <thead class="border border-gray-200 dark:border-gray-800 bg-gray-200 dark:bg-gray-800 text-xs font-medium uppercase text-gray-700 dark:text-gray-200">
                    <tr>
                        <th scope="col" class="px-8 py-4">
                            Lecture
                        </th>

                        <th scope="col" class="px-8 py-4">
                            Attended
                        </th>

                        <th scope="col" class="px-8 py-4">
                            Paid?
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-800 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400">
                    @foreach($lectures as $lecture)
                        <tr>
                            <td class="px-8 py-6">{{ $lecture->start_date->format('l, d F Y h:i A') }}</td>

                            @if($lecture->attendances()->whereBelongsTo($this->record->enrollment)->exists())
                                <td class="px-8 py-6"><x-heroicon-s-badge-check class="text-success-500 h-6 w-6" /></td>
                            @else
                                <td class="px-8 py-6"><x-heroicon-s-x-circle class="text-danger-500 h-6 w-6" /></td>
                            @endif

                            @if($lecture->attendances()->whereBelongsTo($this->record->enrollment)->wherePaid(true)->exists())
                                <td class="px-8 py-6"><x-heroicon-s-badge-check class="text-success-500 h-6 w-6" /></td>
                            @else
                                <td class="px-8 py-6"><x-heroicon-s-x-circle class="text-danger-500 h-6 w-6" /></td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if($assessments->isNotEmpty())
        <div class="mt-16 col-span-4 flex flex-col">
            <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Assessments</h2>

            <div class="mt-4 overflow-hidden rounded">
                <table class="w-full text-left text-sm text-gray-500">
                    <thead class="border border-gray-200 dark:border-gray-800 bg-gray-200 dark:bg-gray-800 text-xs font-medium uppercase text-gray-700 dark:text-gray-200">
                        <tr>
                            <th scope="col" class="px-8 py-4">
                                Type
                            </th>

                            <th scope="col" class="px-8 py-4">
                                Topic
                            </th>

                            <th scope="col" class="px-8 py-4">
                                Submitted?
                            </th>

                            <th scope="col" class="px-8 py-4">
                                Score
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800 border border-gray-200 dark:border-gray-800 text-gray-500 dark:text-gray-400">
                        @foreach($assessments as $assessment)
                            <tr>
                                <td class="px-8 py-6">{{ $assessment->type }}</td>
                                <td class="px-8 py-6">{{ $assessment->topic }}</td>

                                @if($assessment->submissions()->whereBelongsTo($this->record->enrollment)->exists())
                                    <td class="px-8 py-6">{{ $assessment->submissions()->whereBelongsTo($this->record->enrollment)->first()->submission_date->format('l, d F Y h:i A') }}</td>
                                @else
                                    <td class="px-8 py-6"><x-heroicon-s-x-circle class="text-danger-500 h-6 w-6" /></td>
                                @endif

                                @if($assessment->submissions()->whereBelongsTo($this->record->enrollment)->exists())
                                    <td class="px-8 py-6">{{ $assessment->submissions()->whereBelongsTo($this->record->enrollment)->first()->score }} / {{ $assessment->max_score }}</td>
                                @else
                                    <td class="px-8 py-6"><x-heroicon-s-x-circle class="text-danger-500 h-6 w-6" /></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    <div class="mt-16 col-span-4 flex flex-col">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-gray-200">Remarks</h2>

        <span class="mt-4 font-medium tracking-tight text-gray-500 dark:text-gray-400">{{ $this->record->remarks ?? 'No Remarks' }}</span>
    </div>
</div>
