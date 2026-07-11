@extends('errors.layout')

@section('title', 'Page Not Found')
@section('code', '404')
@section('icon', 'explore_off')
@section('message_title', 'Page Not Found')
@section('message_body', $exception->getMessage() ?: 'The page you are looking for does not exist, has been removed, or is temporarily unavailable.')
