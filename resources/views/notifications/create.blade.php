   @extends($layout)

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Send Notification</h1>
            <p class="text-gray-500 text-sm mt-1">Send alerts/messages to students, teachers, or classes</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 text-green-600 p-4 rounded-xl text-sm font-medium border border-green-100 flex items-center gap-2">
        <i class="fa-solid fa-check-circle"></i>
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 text-red-600 p-4 rounded-xl text-sm font-medium border border-red-100 flex items-center gap-2">
        <i class="fa-solid fa-circle-exclamation"></i>
        {{ session('error') }}
    </div>
    @endif

    @php
    $formAction = route('admin.notifications.store');
    if(request()->routeIs('accountant.*')) {
    $formAction = route('accountant.notifications.store');
    } elseif(request()->routeIs('student.*')) {
    $formAction = route('student.notifications.store');
    }
    @endphp

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 max-w-2xl">
        <form action="{{ $formAction }}" method="POST"
            x-data="{ 
                  audience: '', 
                  selectedClassForStudent: '', 
                  studentsList: [],
                  loadingStudents: false,
                  async fetchStudents() {
                      if (!this.selectedClassForStudent) {
                          this.studentsList = [];
                          return;
                      }
                      this.loadingStudents = true;
                      // Determine base path via JS or blade interpolation
                      let prefix = '{{ request()->routeIs('accountant.*') ? 'accountant' : 'admin' }}';
                      let url = `/${prefix}/api/classes/${this.selectedClassForStudent}/students`;
                      
                      try {
                          let response = await fetch(url);
                          this.studentsList = await response.json();
                      } catch (e) {
                          console.error('Error fetching students:', e);
                      } finally {
                          this.loadingStudents = false;
                      }
                  }
              }">
            @csrf

            <div class="space-y-6">
                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" name="title" id="title" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none" placeholder="e.g., Important Announcement">
                </div>

                <!-- Message -->
                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                    <textarea name="message" id="message" rows="4" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none" placeholder="Write your message here..."></textarea>
                </div>

                <!-- Audience Selector -->
                <div>
                    <label for="audience" class="block text-sm font-medium text-gray-700 mb-1">Target Audience</label>
                    <select name="audience" id="audience" x-model="audience" required class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                        <option value="" disabled selected>Select Audience</option>
                        @if(request()->routeIs('student.*'))
                        <option value="all_teachers">All Teachers</option>
                        <option value="accountant">Accountant</option>
                        @else
                        <option value="all_students">All Students</option>
                        <option value="all_teachers">All Teachers</option>
                        <option value="student">Specific Student</option>
                        <option value="teacher">Specific Teacher</option>
                        <option value="class">Specific Class</option>
                        <option value="everyone">Everyone (All Students & Teachers)</option>
                        @endif
                    </select>
                </div>

                <!-- Conditional Fields -->
                <div x-show="audience === 'class'" x-cloak class="transition-all">
                    <label for="class_id" class="block text-sm font-medium text-gray-700 mb-1">Select Class</label>
                    <select name="class_id" id="class_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                        <option value="">Select a Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div x-show="audience === 'student'" x-cloak class="transition-all space-y-4">
                    <!-- Filter by Class -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Filter by Class (Optional)</label>
                        <select x-model="selectedClassForStudent" @change="fetchStudents()" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            <option value="">Select Class to Filter</option>
                            @foreach($classes as $class)
                            <option value="{{ $class->id }}">{{ $class->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Student Select -->
                    <div>
                        <label for="student_id" class="block text-sm font-medium text-gray-700 mb-1">Select Student</label>
                        <select name="student_id" id="student_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                            <option value="">Select a Student</option>

                            <!-- If no class selected, show all (removed to prevent memory crash) -->

                            <!-- Dynamic Options -->
                            <template x-for="student in studentsList" :key="student.id">
                                <option :value="student.id" x-text="student.name + ' (' + (student.email || 'No Email') + ')'"></option>
                            </template>
                        </select>
                        <p x-show="!selectedClassForStudent" class="text-xs text-blue-600 mt-1">Please select a class first to see students.</p>
                        <p x-show="loadingStudents" class="text-xs text-purple-600 mt-1 animate-pulse">Loading students...</p>
                        <p x-show="selectedClassForStudent && !loadingStudents && studentsList.length === 0" class="text-xs text-red-500 mt-1">No students found in this class.</p>
                    </div>
                </div>

                <div x-show="audience === 'teacher'" x-cloak class="transition-all">
                    <label for="teacher_id" class="block text-sm font-medium text-gray-700 mb-1">Select Teacher</label>
                    <select name="teacher_id" id="teacher_id" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all outline-none">
                        <option value="">Select a Teacher</option>
                        @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->name }}</option>
                        @endforeach
                    </select>
                    @if(count($teachers) === 0)
                    <p class="text-xs text-red-500 mt-1">Too many teachers to list. Search functionality coming soon.</p>
                    @endif
                </div>

                <!-- Submit -->
                <button type="submit" class="w-full px-6 py-3 bg-purple-600 text-white rounded-xl font-medium hover:bg-purple-700 transition-colors shadow-lg shadow-purple-600/20 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-paper-plane"></i>
                    Send Notification
                </button>
            </div>
        </form>
    </div>
</div>
@endsection