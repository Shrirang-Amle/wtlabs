from django.urls import path
from .views import home
from main import views

urlpatterns = [
    path('', home, name='home'),
    path('personal/', views.personal, name='personal'),
    path('education/', views.education, name='education'),
    path('skills/', views.skills, name='skills'),
    path('projects/', views.projects, name='projects'),
    path('certifications/', views.certifications, name='certifications'),
    path('contact/', views.contact, name='contact'),
]
