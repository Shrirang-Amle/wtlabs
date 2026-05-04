from django.shortcuts import render

# Create your views here.
def home(request):
    return render(request, 'main/home.html')

def personal(request):
    return render(request, 'main/personal.html')

def education(request):
    return render(request, 'main/education.html')

def skills(request):
    return render(request, 'main/skills.html')

def projects(request):
    return render(request, 'main/projects.html')

def certifications(request):
    return render(request, 'main/certifications.html')

def contact(request):
    if request.method == "POST":
        name = request.POST.get('name')
        email = request.POST.get('email')
        message = request.POST.get('message')

        print(name, email, message)

    return render(request, 'main/contact.html')
