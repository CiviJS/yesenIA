
document.addEventListener('submit', function (event) {
    const form = event.target;
    
    const method = form.getAttribute('method')?.toLowerCase();
    if (method === 'post' || method === 'put' || method === 'patch') {
        
        if (!form.querySelector('input[name="X-Idempotency-Key"]')) {
            
            const token = crypto.randomUUID();
            
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'X-Idempotency-Key'; 
            hiddenInput.value = token;
            form.appendChild(hiddenInput);
            
            console.log('Key global inyectada en el formulario nativo :D ', token);
        }
    }
});
