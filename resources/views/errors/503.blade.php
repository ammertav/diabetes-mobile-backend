@extends('errors.layout')

@section('title', 'Service Unavailable')
@section('code', '503')
@section('icon', 'construction')
@section('message_title', 'Under Maintenance')
@section('message_body', $exception->getMessage() ?: 'Our systems are undergoing scheduled maintenance to improve our clinical services. Please check back shortly.')
