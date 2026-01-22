<!DOCTYPE html>
<html>
<head>
    <title>Debug Test</title>
</head>
<body>
    <h1>Debug Test Page</h1>
    
    <button id="test-btn">Test Button</button>
    
    <div id="output" style="margin-top: 20px; padding: 10px; border: 1px solid #ccc;"></div>

    <script>
        console.log('🔧 DEBUG: Simple test page script loaded');
        
        document.getElementById('test-btn').addEventListener('click', function() {
            console.log('🎯 Test button clicked');
            document.getElementById('output').innerHTML = 'Button clicked at ' + new Date().toLocaleTimeString();
            
            // Test fetch
            fetch('/test-route', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({test: 'data'})
            })
            .then(response => response.json())
            .then(data => {
                console.log('📨 Fetch response:', data);
                document.getElementById('output').innerHTML += '<br>Fetch response: ' + JSON.stringify(data);
            })
            .catch(error => {
                console.error('❌ Fetch error:', error);
                document.getElementById('output').innerHTML += '<br>Fetch error: ' + error.message;
            });
        });
    </script>
</body>
</html>