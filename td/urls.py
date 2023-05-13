from django.urls import path

from . import views

urlpatterns = [
    path("", views.index, name="index"),
    path("ai/<int:reward>", views.request_ai_action, name="ai")
]
