<x-app-layout>
    <x-slot name="header">
        <div class="bg-blue-600 p-4 rounded-t-lg shadow-md">
            <h2 class="text-white text-2xl font-semibold">
                {{ __('Messages') }}
            </h2>
        </div>
    </x-slot>


    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <!-- New Message Button to Open Modal -->
                <button onclick="openNewMessageModal()" class="bg-blue-600 text-white py-2 px-4 rounded">New Message</button><br><br>
                
                <!-- Inbox -->
                <h3 class="font-semibold text-lg text-gray-800 mb-2">Inbox</h3>
                <div class="max-h-64 overflow-y-auto"> <!-- Set a maximum height and enable scrolling -->
                    <ul class="bg-gray-100 rounded-lg divide-y divide-gray-200">
                        @forelse ($formattedConversations as $conversation)
                            <li class="p-4 flex justify-between items-center hover:bg-gray-200 transition duration-150 cursor-pointer">
                                <div class="flex-1" onclick="openInboxModal('{{ $conversation['otherUserId'] }}', '{{ $conversation['otherUserName'] }}')">
                                    <strong class="text-gray-800">{{ $conversation['otherUserName'] }}</strong>
                                    <p class="text-gray-600">{{ $conversation['latestMessage']->content ?? 'No messages' }}</p>
                                    <small class="text-gray-500">{{ $conversation['latestMessage']->created_at->diffForHumans() ?? 'N/A' }}</small>
                                </div>
                                <div>
                                    <button 
                                        onclick="openDeleteModal('{{ $conversation['latestMessage']->id ?? '' }}', this)" 
                                        class="text-red-600 hover:text-red-800 ml-4">
                                        Delete
                                    </button>
                                </div>
                            </li>
                        @empty
                            <li class="p-4">No messages in your inbox.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- New Message Modal -->
    <div id="newMessageModal" class="fixed inset-0 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="font-semibold text-lg">New Message</h3>
            <form method="POST" action="{{ route('messages.send') }}">
                @csrf
                <div class="mb-4">
                    <label for="receiver_id" class="block text-gray-700">Send to:</label>
                    <select name="receiver_id" id="receiver_id" required class="border rounded-md p-2 w-full">
                        <option value="">Select a recipient</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-4">
                    <label for="content" class="block text-gray-700">Message:</label>
                    <textarea name="content" id="content" rows="4" required class="border rounded-md p-2 w-full"></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Send Message</button>
                    <button type="button" onclick="closeNewMessageModal()" class="bg-gray-300 text-gray-700 py-2 px-4 rounded">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Message Modal -->
    <div id="messageModal" class="fixed inset-0 flex items-center justify-center hidden bg-black bg-opacity-50">
        <div class="bg-white rounded-lg p-4 max-w-lg w-full max-h-[80vh] overflow-hidden">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-lg" id="modalUserName"></h3>
                <button onclick="closeInboxModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
            </div>
            <div class="max-h-60 overflow-y-auto mb-4"> <!-- Set a max height and enable scrolling for messages -->
                <ul id="conversationMessages" class="divide-y divide-gray-200">
                    <!-- Messages will be appended here -->
                </ul>
            </div>
            <form id="replyForm" method="POST" action="{{ route('messages.send') }}">
                @csrf
                <input type="hidden" name="receiver_id" id="replyReceiverId">
                <div class="mb-4">
                    <label for="replyContent" class="block text-gray-700">Reply:</label>
                    <textarea name="content" id="replyContent" rows="2" required class="border rounded-md p-2 w-full"></textarea>
                </div>
                <div class="flex justify-between">
                    <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded">Send Reply</button>
                    <button type="button" onclick="closeInboxModal()" class="bg-gray-300 text-gray-700 py-2 px-4 rounded">Close</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="fixed inset-0 flex items-center justify-center hidden bg-gray-800 bg-opacity-50">
        <div class="bg-white rounded-lg p-4 w-1/3">
            <h2 class="text-lg font-semibold">Confirm Deletion</h2>
            <p>Are you sure you want to delete this message?</p>
            <div class="flex justify-end mt-4">
                <button id="cancelDelete" class="bg-gray-300 text-gray-700 px-4 py-2 rounded mr-2" onclick="closeDeleteModal()">Cancel</button>
                <button id="confirmDelete" class="bg-red-600 text-white px-4 py-2 rounded" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

    <script>
        let messageIdToDelete;

        function openDeleteModal(messageId) {
            messageIdToDelete = messageId; // Store the message ID
            document.getElementById('deleteModal').classList.remove('hidden'); // Show the modal
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden'); // Hide the modal
        }

        function confirmDelete() {
            $.ajax({
                url: '/messages/' + messageIdToDelete + '/delete',
                method: 'DELETE',
                success: function(response) {
                    if (response.success) {
                        alert('Message deleted successfully.');
                        location.reload(); // Refresh the page or update the UI
                    } else {
                        alert('Error deleting message. Please try again.');
                    }
                },
                error: function() {
                    alert('Error deleting message. Please try again.');
                }
            });
            closeDeleteModal(); // Hide the modal after confirming deletion
        }

        function openNewMessageModal() {
            document.getElementById('newMessageModal').classList.remove('hidden'); // Show the new message modal
        }

        function closeNewMessageModal() {
            document.getElementById('newMessageModal').classList.add('hidden'); // Hide the new message modal
        }

        function openInboxModal(otherUserId, otherUserName) {
        document.getElementById('modalUserName').innerText = `Chat with ${otherUserName}`;
        document.getElementById('replyReceiverId').value = otherUserId;

        // Mark the conversation as read
        fetch(`/messages/conversation/${otherUserId}/read`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token for Laravel
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to mark conversation as read');
            }
            return response.json(); // Convert response to JSON
        })
        .then(data => {
            console.log('Marked as read:', data); // Log success response
        })
        .catch(error => {
            console.error('Error:', error); // Log any errors
        });

        // Fetch messages for this conversation
        fetch(`/messages/conversation/${otherUserId}`)
            .then(response => response.json())
            .then(messages => {
                const conversationMessages = document.getElementById('conversationMessages');
                conversationMessages.innerHTML = ''; // Clear previous messages

                messages.forEach(message => {
                    const messageItem = document.createElement('li');
                    messageItem.className = 'py-2';
                    messageItem.innerHTML = ` 
                        <strong>${message.sender_id == {{ Auth::id() }} ? 'You' : message.sender.name}:</strong> 
                        ${message.content} 
                        <small class="text-gray-500">${new Date(message.created_at).toLocaleString()}</small>
                    `;
                    conversationMessages.appendChild(messageItem);
                });

                document.getElementById('messageModal').classList.remove('hidden'); // Show modal
                // Scroll to the bottom of the messages
                conversationMessages.scrollTop = conversationMessages.scrollHeight;
            });
        }   

        function closeInboxModal() {
            document.getElementById('messageModal').classList.add('hidden'); // Hide modal
        }
    </script>
</x-app-layout>